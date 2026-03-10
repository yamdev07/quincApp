<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Client;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vérifier que la vente appartient à la quincaillerie
     */
    private function authorizeSaleAccess(Sale $sale)
    {
        if (!Auth::user()->hasAccessTo($sale)) {
            abort(403, 'Vous n\'avez pas accès à cette vente.');
        }
    }

    /**
     * Vérifier les permissions de gestion des ventes
     */
    private function authorizeSalesAccess()
    {
        if (!Auth::user()->canManageSales()) {
            abort(403, 'Vous n\'avez pas les droits pour gérer les ventes.');
        }
    }

    /**
     * Vérifier les permissions d'administration (pour suppression)
     */
    private function authorizeAdmin()
    {
        if (!Auth::user()->isSuperAdminOrAdmin()) {
            abort(403, 'Action réservée aux administrateurs.');
        }
    }

    // ----------------------
    // Liste des ventes
    // ----------------------
    public function index(Request $request)
    {
        $this->authorizeSalesAccess();
        
        $query = Sale::with(['items.product', 'client', 'user'])
                     ->withSum('items', 'quantity');
        
        // Filtres optionnels
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('min_amount')) {
            $query->where('total_price', '>=', $request->min_amount);
        }
        
        if ($request->filled('max_amount')) {
            $query->where('total_price', '<=', $request->max_amount);
        }
        
        $sales = $query->latest()->paginate(10);
        
        // Pour les filtres
        $clients = Client::sameCompany()->get();
        $users = Auth::user()->employees()->get(); // Les caissiers de la quincaillerie
        
        return view('sales.index', compact('sales', 'clients', 'users'));
    }

    // ----------------------
    // Formulaire de création
    // ----------------------
    public function create()
    {
        $this->authorizeSalesAccess();
        
        // Seuls les produits avec du STOCK disponible et de la même quincaillerie
        $products = Product::where('stock', '>', 0)
                          ->orderBy('name')
                          ->get();
        
        $clients = Client::sameCompany()->get();

        return view('sales.create', compact('products', 'clients'));
    }

    // ----------------------
    // Enregistrer une vente
    // ----------------------
    public function store(Request $request)
    {
        $this->authorizeSalesAccess();
        
        \Log::info('=== DÉBUT VENTE ===');
        \Log::info('Données brutes reçues:', $request->all());
        
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);
        
        // Vérifier que le client appartient à la même quincaillerie
        if ($request->client_id) {
            $client = Client::sameCompany()->find($request->client_id);
            if (!$client) {
                return back()->with('error', 'Client invalide.')->withInput();
            }
        }
        
        \Log::info('Données validées:', $validated);
        
        DB::transaction(function () use ($validated) {
            \Log::info('Transaction DB démarrée');
            
            // Créer la vente (owner_id sera auto-assigné par le Trait)
            $sale = Sale::create([
                'client_id' => $validated['client_id'],
                'user_id' => Auth::id(),
                'total_price' => 0,
            ]);
            
            \Log::info("Vente créée - ID: {$sale->id}");
            
            // Récupérer le client pour le motif
            $clientName = 'Client';
            if ($validated['client_id']) {
                $client = Client::find($validated['client_id']);
                $clientName = $client ? $client->name : 'Client';
            }
            
            $grandTotal = 0;
            
            foreach ($validated['products'] as $index => $productData) {
                \Log::info("Produit #{$index}:", $productData);
                
                // Récupérer le produit avec verrou et vérifier qu'il appartient à la même quincaillerie
                $product = Product::lockForUpdate()->find($productData['product_id']);
                
                if (!$product) {
                    \Log::error("Produit non trouvé: " . $productData['product_id']);
                    continue;
                }
                
                // Vérifier que le produit appartient à la même quincaillerie
                if (!Auth::user()->hasAccessTo($product)) {
                    throw new \Exception("Le produit '{$product->name}' n'est pas disponible.");
                }
                
                \Log::info("Produit trouvé: {$product->name} (ID: {$product->id})");
                \Log::info("Stock AVANT vente: {$product->stock}");
                
                $quantityToSell = $productData['quantity'];
                $unitPrice = $productData['unit_price'];
                
                // Vérifier le stock
                if ($product->stock < $quantityToSell) {
                    $message = "Stock insuffisant pour '{$product->name}'. Stock: {$product->stock}, Demandé: {$quantityToSell}";
                    \Log::error($message);
                    throw new \Exception($message);
                }
                
                // Calculer le stock après vente
                $stockAfter = $product->stock - $quantityToSell;
                
                // ============================================
                // ENREGISTRER LE MOUVEMENT DE STOCK
                // ============================================
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'sortie',
                    'quantity' => $quantityToSell,
                    'purchase_price' => $product->purchase_price,
                    'sale_price' => $unitPrice,
                    'stock_after' => $stockAfter,
                    'motif' => "Vente #{$sale->id} à {$clientName}",
                    'reference_document' => 'VTE-' . $sale->id,
                    'user_id' => Auth::id(),
                    'owner_id' => $product->owner_id, // Important pour l'isolation
                ]);
                
                \Log::info("Mouvement de stock enregistré pour produit ID: {$product->id}");
                
                // Créer l'item de vente
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantityToSell,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * $quantityToSell,
                ]);
                
                \Log::info("SaleItem créé pour produit ID: {$product->id}");
                
                // DÉDUCTION DU STOCK
                $product->decrement('stock', $quantityToSell);
                
                // Recharger le produit depuis la base
                $product->refresh();
                
                \Log::info("Stock APRÈS vente: {$product->stock}");
                \Log::info("---");
                
                $grandTotal += ($unitPrice * $quantityToSell);
            }
            
            // Mettre à jour le total de la vente
            $sale->update(['total_price' => $grandTotal]);
            \Log::info("Total vente: {$grandTotal} CFA");
        });
        
        \Log::info('=== FIN VENTE ===');
        
        return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès !');
    }

    // ----------------------
    // Afficher une vente
    // ----------------------
    public function show($id)
    {
        $this->authorizeSalesAccess();
        
        $sale = Sale::with(['items.product', 'client', 'user'])->findOrFail($id);
        $this->authorizeSaleAccess($sale);
        
        // Calculer la quantité totale pour la vue
        $totalQuantity = $sale->items->sum('quantity');
        
        return view('sales.show', compact('sale', 'totalQuantity'));
    }

    // ----------------------
    // Supprimer une vente (annulation)
    // ----------------------
    public function destroy($id)
    {
        $this->authorizeAdmin(); // Seuls les admins peuvent supprimer
        
        $sale = Sale::findOrFail($id);
        $this->authorizeSaleAccess($sale);

        DB::transaction(function () use ($sale) {
            // Récupérer le client pour le motif
            $clientName = $sale->client ? $sale->client->name : 'Client';
            
            // RÉTABLIR le STOCK pour chaque produit vendu
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    // Vérifier que le produit appartient à la même quincaillerie
                    if (!Auth::user()->hasAccessTo($product)) {
                        throw new \Exception("Le produit '{$product->name}' ne vous appartient pas.");
                    }
                    
                    // Calculer le nouveau stock après annulation
                    $stockAfter = $product->stock + $item->quantity;
                    
                    // ============================================
                    // ENREGISTRER LE MOUVEMENT D'ANNULATION
                    // ============================================
                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'entree',
                        'quantity' => $item->quantity,
                        'purchase_price' => $product->purchase_price,
                        'sale_price' => $product->sale_price,
                        'stock_after' => $stockAfter,
                        'motif' => "Annulation vente #{$sale->id} à {$clientName}",
                        'reference_document' => 'ANNUL-VTE-' . $sale->id,
                        'user_id' => Auth::id(),
                        'owner_id' => $product->owner_id,
                    ]);
                    
                    // REMETTRE dans le STOCK la quantité qui avait été vendue
                    $product->increment('stock', $item->quantity);
                }
            }

            // Supprimer les items de vente
            $sale->items()->delete();

            // Supprimer la vente
            $sale->delete();
        });

        return redirect()->route('sales.index')->with('success', 'Vente annulée et stock rétabli avec succès !');
    }

    // ----------------------
    // Générer une facture
    // ----------------------
    public function invoice($id)
    {
        $this->authorizeSalesAccess();
        
        $sale = Sale::with(['items.product', 'client', 'user'])->findOrFail($id);
        $this->authorizeSaleAccess($sale);
        
        $totalQuantity = $sale->items->sum('quantity');
        
        return view('sales.invoice', compact('sale', 'totalQuantity'));
    }

    // ----------------------
    // API pour les statistiques
    // ----------------------
    public function getStats()
    {
        $this->authorizeSalesAccess();
        
        $stats = [
            'total_sales' => Sale::count(),
            'total_revenue' => Sale::sum('total_price'),
            'total_quantity_sold' => SaleItem::sum('quantity'),
            'average_sale' => Sale::avg('total_price'),
            'unique_clients' => Sale::distinct('client_id')->count('client_id'),
            'active_cashiers' => Sale::distinct('user_id')->count('user_id'),
        ];
        
        return response()->json($stats);
    }

    // ----------------------
    // Ventes par période
    // ----------------------
    public function salesByPeriod(Request $request)
    {
        $this->authorizeSalesAccess();
        
        $period = $request->get('period', 'today');
        
        $query = Sale::query();
        
        switch ($period) {
            case 'today':
                $query->whereDate('created_at', today());
                break;
            case 'week':
                $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                break;
            case 'month':
                $query->whereMonth('created_at', now()->month);
                break;
            case 'year':
                $query->whereYear('created_at', now()->year);
                break;
        }
        
        $sales = $query->with(['items.product', 'client'])
                       ->latest()
                       ->paginate(10);
        
        return view('sales.index', compact('sales', 'period'));
    }
    
    // ----------------------
    // Mettre à jour une vente
    // ----------------------
    public function update(Request $request, $id)
    {
        $this->authorizeAdmin(); // Seuls les admins peuvent modifier
        
        $sale = Sale::findOrFail($id);
        $this->authorizeSaleAccess($sale);
        
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            // Ajoutez d'autres règles selon vos besoins
        ]);
        
        // Vérifier le client
        if ($request->client_id) {
            $client = Client::sameCompany()->find($request->client_id);
            if (!$client) {
                return back()->with('error', 'Client invalide.');
            }
        }
        
        DB::transaction(function () use ($sale, $validated) {
            // Logique pour mettre à jour une vente existante
            // À implémenter selon vos besoins spécifiques
            
            $sale->update($validated);
        });
        
        return redirect()->route('sales.show', $sale->id)
            ->with('success', 'Vente mise à jour avec succès.');
    }

    // ----------------------
    // Rapport des ventes
    // ----------------------
    public function salesReport(Request $request)
    {
        if (!Auth::user()->canViewReports()) {
            abort(403, 'Vous n\'avez pas les droits pour voir les rapports.');
        }
        
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $query = Sale::with(['client', 'user', 'items.product']);
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        $sales = $query->latest()->get();
        
        // Calculer les statistiques
        $totalRevenue = $sales->sum('total_price');
        $totalItems = $sales->flatMap->items->sum('quantity');
        $averageSale = $sales->avg('total_price') ?? 0;
        
        // Ventes par jour
        $salesByDay = $sales->groupBy(function($sale) {
            return $sale->created_at->format('Y-m-d');
        })->map(function($daySales) {
            return [
                'count' => $daySales->count(),
                'total' => $daySales->sum('total_price'),
            ];
        });
        
        // Produits les plus vendus
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_quantity'), DB::raw('SUM(sale_items.total_price) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
        
        $reportData = [
            'total_sales' => $sales->count(),
            'total_revenue' => $totalRevenue,
            'formatted_total_revenue' => number_format($totalRevenue, 0, ',', ' ') . ' FCFA',
            'total_items' => $totalItems,
            'average_sale' => $averageSale,
            'formatted_average_sale' => number_format($averageSale, 0, ',', ' ') . ' FCFA',
            'sales_by_day' => $salesByDay,
            'top_products' => $topProducts,
        ];
        
        // Pour les filtres
        $clients = Client::sameCompany()->get();
        $users = Auth::user()->employees()->get();
        
        return view('reports.sales', compact('sales', 'reportData', 'clients', 'users'));
    }
}