<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Client;
use App\Models\User;
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
     * Récupérer le tenant_id de l'utilisateur connecté
     */
    private function getTenantId()
    {
        return Auth::user()->tenant_id;
    }

    /**
     * Vérifier que la vente appartient à la quincaillerie
     */
    private function authorizeSaleAccess(Sale $sale)
    {
        if ($sale->tenant_id != $this->getTenantId()) {
            abort(403, 'Vous n\'avez pas accès à cette vente.');
        }
    }

    /**
     * Vérifier les permissions de visualisation des ventes
     */
    private function authorizeViewSales()
    {
        return true;
    }

    /**
     * Vérifier les permissions de création/modification des ventes
     * ✅ CAISSIER MAINTENANT AUTORISÉ
     */
    private function authorizeManageSales()
    {
        $userRole = Auth::user()->role;
        
        // Tous les rôles qui peuvent créer/modifier des ventes
        $allowedRoles = ['super_admin_global', 'super_admin', 'admin', 'manager', 'cashier'];
        
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Vous n\'avez pas l\'autorisation de créer ou modifier des ventes.');
        }
        
        return true;
    }

    /**
     * Vérifier les permissions d'annulation (admin, manager, storekeeper)
     * ⚠️ Les caissiers ne peuvent PAS annuler les ventes
     */
    private function authorizeCancelSale()
    {
        $userRole = Auth::user()->role;
        $allowedRoles = ['super_admin_global', 'super_admin', 'admin', 'manager', 'storekeeper'];
        
        if (!in_array($userRole, $allowedRoles)) {
            abort(403, 'Vous n\'avez pas l\'autorisation d\'annuler des ventes.');
        }
        
        return true;
    }

    // ----------------------
    // Liste des ventes
    // ----------------------
    public function index(Request $request)
    {
        $this->authorizeViewSales();
        
        $tenantId = $this->getTenantId();
        
        $query = Sale::with(['items.product', 'client', 'user'])
                     ->withSum('items', 'quantity')
                     ->where('tenant_id', $tenantId);
        
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
        $clients = Client::where('tenant_id', $tenantId)->get();
        $users = User::where('tenant_id', $tenantId)
                     ->where('role', '!=', 'super_admin_global')
                     ->get();
        
        return view('sales.index', compact('sales', 'clients', 'users'));
    }

    // ----------------------
    // Formulaire de création
    // ----------------------
    public function create()
    {
        $this->authorizeManageSales();
        
        $tenantId = $this->getTenantId();
        
        $products = Product::where('stock', '>', 0)
                          ->where('tenant_id', $tenantId)
                          ->orderBy('name')
                          ->get();
        
        $clients = Client::where('tenant_id', $tenantId)->get();

        return view('sales.create', compact('products', 'clients'));
    }

    // ----------------------
    // Enregistrer une vente
    // ----------------------
    public function store(Request $request)
    {
        $this->authorizeManageSales();
        
        \Log::info('=== DÉBUT VENTE ===');
        \Log::info('Données brutes reçues:', $request->all());
        
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'products' => 'required|array|min:1',
            'products.*.product_id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'products.*.unit_price' => 'required|numeric|min:0',
        ]);
        
        $tenantId = $this->getTenantId();
        $userId = Auth::id();
        
        // Vérifier que le client appartient à la même quincaillerie
        if ($request->client_id) {
            $client = Client::where('tenant_id', $tenantId)->find($request->client_id);
            if (!$client) {
                return back()->with('error', 'Client invalide.')->withInput();
            }
        }
        
        DB::transaction(function () use ($validated, $tenantId, $userId) {
            // Créer la vente
            $sale = Sale::create([
                'client_id' => $validated['client_id'],
                'user_id' => $userId,
                'total_price' => 0,
                'tenant_id' => $tenantId,
            ]);
            
            \Log::info("Vente créée - ID: {$sale->id}");
            
            $clientName = 'Client';
            if ($validated['client_id']) {
                $client = Client::find($validated['client_id']);
                $clientName = $client ? $client->name : 'Client';
            }
            
            $grandTotal = 0;
            
            foreach ($validated['products'] as $index => $productData) {
                $product = Product::lockForUpdate()->find($productData['product_id']);
                
                if (!$product) {
                    throw new \Exception("Produit non trouvé: " . $productData['product_id']);
                }
                
                if ($product->tenant_id != $tenantId) {
                    throw new \Exception("Le produit '{$product->name}' n'est pas disponible.");
                }
                
                $quantityToSell = $productData['quantity'];
                $unitPrice = $productData['unit_price'];
                
                if ($product->stock < $quantityToSell) {
                    throw new \Exception("Stock insuffisant pour '{$product->name}'. Stock: {$product->stock}, Demandé: {$quantityToSell}");
                }
                
                $stockAfter = $product->stock - $quantityToSell;
                
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'sortie',
                    'quantity' => $quantityToSell,
                    'purchase_price' => $product->purchase_price,
                    'sale_price' => $unitPrice,
                    'stock_after' => $stockAfter,
                    'motif' => "Vente #{$sale->id} à {$clientName}",
                    'reference_document' => 'VTE-' . $sale->id,
                    'user_id' => $userId,
                    'tenant_id' => $tenantId,
                ]);
                
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $quantityToSell,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * $quantityToSell,
                    'tenant_id' => $tenantId,
                ]);
                
                $product->decrement('stock', $quantityToSell);
                
                $grandTotal += ($unitPrice * $quantityToSell);
            }
            
            $sale->update(['total_price' => $grandTotal]);
        });
        
        return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès !');
    }

    // ----------------------
    // Afficher une vente
    // ----------------------
    public function show($id)
    {
        $this->authorizeViewSales();
        
        $tenantId = $this->getTenantId();
        
        $sale = Sale::with(['items.product', 'client', 'user'])
                    ->where('tenant_id', $tenantId)
                    ->findOrFail($id);
        
        $totalQuantity = $sale->items->sum('quantity');
        
        return view('sales.show', compact('sale', 'totalQuantity'));
    }

    // ----------------------
    // ANNULER une vente
    // ----------------------
    public function cancel($id)
    {
        $this->authorizeCancelSale();
        
        $tenantId = $this->getTenantId();
        $userId = Auth::id();
        
        $sale = Sale::where('tenant_id', $tenantId)->findOrFail($id);
        
        DB::transaction(function () use ($sale, $tenantId, $userId) {
            $clientName = $sale->client ? $sale->client->name : 'Client';
            
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                
                if ($product) {
                    if ($product->tenant_id != $tenantId) {
                        throw new \Exception("Le produit '{$product->name}' ne vous appartient pas.");
                    }
                    
                    $stockAfter = $product->stock + $item->quantity;
                    
                    StockMovement::create([
                        'product_id' => $product->id,
                        'type' => 'entree',
                        'quantity' => $item->quantity,
                        'purchase_price' => $product->purchase_price,
                        'sale_price' => $item->unit_price,
                        'stock_after' => $stockAfter,
                        'motif' => "Annulation vente #{$sale->id} à {$clientName}",
                        'reference_document' => 'ANNUL-VTE-' . $sale->id,
                        'user_id' => $userId,
                        'tenant_id' => $tenantId,
                    ]);
                    
                    $product->increment('stock', $item->quantity);
                }
            }
            
            // Supprimer la vente
            $sale->delete();
        });
        
        return redirect()->route('sales.index')->with('success', 'Vente annulée et stock restauré.');
    }

    // ----------------------
    // Générer une facture
    // ----------------------
    public function invoice($id)
    {
        $this->authorizeViewSales();

        $tenantId = $this->getTenantId();

        $sale = Sale::with(['items.product', 'client', 'user'])
                    ->where('tenant_id', $tenantId)
                    ->findOrFail($id);

        $totalQuantity = $sale->items->sum('quantity');
        $tenant = Auth::user()->tenant;

        return view('sales.invoice', compact('sale', 'totalQuantity', 'tenant'));
    }

    // ----------------------
    // API pour les statistiques
    // ----------------------
    public function getStats()
    {
        $this->authorizeViewSales();
        
        $tenantId = $this->getTenantId();
        
        $stats = [
            'total_sales' => Sale::where('tenant_id', $tenantId)->count(),
            'total_revenue' => Sale::where('tenant_id', $tenantId)->sum('total_price'),
            'total_quantity_sold' => SaleItem::whereHas('sale', function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })->sum('quantity'),
            'average_sale' => Sale::where('tenant_id', $tenantId)->avg('total_price'),
            'unique_clients' => Sale::where('tenant_id', $tenantId)->distinct('client_id')->count('client_id'),
            'active_cashiers' => Sale::where('tenant_id', $tenantId)->distinct('user_id')->count('user_id'),
        ];
        
        return response()->json($stats);
    }

    // ----------------------
    // Rapport des ventes
    // ----------------------
    public function salesReport(Request $request)
    {
        $userRole = Auth::user()->role;
        $reportRoles = ['super_admin_global', 'super_admin', 'admin', 'manager'];
        
        if (!in_array($userRole, $reportRoles)) {
            abort(403, 'Vous n\'avez pas les droits pour voir les rapports.');
        }
        
        $tenantId = $this->getTenantId();
        
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
        
        $query = Sale::with(['client', 'user', 'items.product'])
                     ->where('tenant_id', $tenantId);
        
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
        
        // Pagination pour l'affichage
        $sales = $query->latest()->paginate(15);
        
        // Toutes les ventes pour les statistiques
        $allSales = Sale::where('tenant_id', $tenantId)->get();
        
        $totalRevenue = $allSales->sum('total_price');
        $totalItems = $allSales->flatMap->items->sum('quantity');
        $averageSale = $allSales->avg('total_price') ?? 0;
        
        // Ventes par jour
        $salesByDay = $allSales->groupBy(function($sale) {
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
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->where('sales.tenant_id', $tenantId)
            ->select('products.name', DB::raw('SUM(sale_items.quantity) as total_quantity'), DB::raw('SUM(sale_items.total_price) as total_revenue'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get();
        
        $reportData = [
            'total_sales' => $allSales->count(),
            'total_revenue' => $totalRevenue,
            'formatted_total_revenue' => number_format($totalRevenue, 0, ',', ' ') . ' FCFA',
            'total_items' => $totalItems,
            'average_sale' => $averageSale,
            'formatted_average_sale' => number_format($averageSale, 0, ',', ' ') . ' FCFA',
            'sales_by_day' => $salesByDay,
            'top_products' => $topProducts,
        ];
        
        // Pour les filtres
        $clients = Client::where('tenant_id', $tenantId)->get();
        $users = User::where('tenant_id', $tenantId)
                     ->where('role', '!=', 'super_admin_global')
                     ->get();
        
        return view('reports.sales', compact('sales', 'reportData', 'clients', 'users'));
    }
}