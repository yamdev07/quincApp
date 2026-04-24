<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Services\PlanService;
use App\Services\ProductService;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ProductController extends Controller
{
    public function __construct(private ProductService $productService)
    {
        $this->middleware('auth');
    }

    private function authorizeStockManagement(): void
    {
        if (!Auth::user()->canManageStock()) {
            abort(403, 'Vous n\'avez pas les droits pour gérer le stock.');
        }
    }

    private function authorizeProductAccess(Product $product): void
    {
        if (!Auth::user()->hasAccessTo($product)) {
            abort(403, 'Vous n\'avez pas accès à ce produit.');
        }
    }

    // 🧱 Liste des produits
    public function index(Request $request)
    {
        $products = $this->productService->applyFilters($request)->paginate(10);

        foreach ($products as $product) {
            $product->stock_summary       = $product->getStockTotals();
            $product->has_multiple_batches = $product->hasMultipleBatches();
        }

        $stats = $this->productService->globalStats();

        return view('products.index', [
            'products'                  => $products,
            'totalProductsGlobal'       => $stats['total'],
            'totalStockGlobal'          => $stats['total_stock'],
            'totalValueGlobal'          => $stats['total_value'],
            'productsWithMultipleBatches' => $stats['multi_batches'],
            'totalStockFiltered'        => $products->sum('stock'),
            'totalValueFiltered'        => $products->sum(fn($p) => ($p->sale_price ?? 0) * ($p->stock ?? 0)),
        ]);
    }
    
    public function search(Request $request)
    {
        return $this->index($request);
    }

    // 🆕 Page d'ajout
    public function create()
    {
        $this->authorizeStockManagement();
        
        $categories = Category::sameCompany()->get();
        $suppliers  = Supplier::sameCompany()->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    // 💾 Enregistrement d'un nouveau produit
    public function store(StoreProductRequest $request)
    {
        $user   = Auth::user();
        $tenant = $user->tenant;

        if (!$user->isSuperAdminGlobal() && $tenant) {
            $plan = PlanService::for($tenant);
            $currentCount = Product::where('tenant_id', $tenant->id)->count();
            if (!$plan->canAddProduct($currentCount)) {
                return back()->with('upgrade', "Limite atteinte : votre plan {$plan->planLabel()} est limité à {$plan->maxProducts()} produits. Passez au plan Business pour continuer.");
            }
        }

        if (!Category::sameCompany()->find($request->category_id)) {
            return back()->with('error', 'Catégorie invalide.');
        }
        if (!Supplier::sameCompany()->find($request->supplier_id)) {
            return back()->with('error', 'Fournisseur invalide.');
        }

        try {
            $result = $this->productService->createOrCumulate(
                $request->validated(),
                $user->tenant_id,
                $user->id
            );

            $message = $result['type'] === 'restocked'
                ? 'Produit existant mis à jour : stock et prix réapprovisionnés.'
                : 'Nouveau produit ajouté avec succès.';

            return redirect()->route('products.index')->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage())->withInput();
        }
    }

    // 👁️ Détails d'un produit AVEC STOCKS GROUPÉS
    public function show($id)
    {
        $product = Product::with(['category', 'supplier'])
            ->withCount('stockMovements')
            ->findOrFail($id);
        
        $this->authorizeProductAccess($product);
        
        $stockByPrice = DB::table('stock_movements')
            ->select(
                DB::raw('purchase_price'),
                DB::raw('reference_document'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('MAX(created_at) as last_update')
            )
            ->where('product_id', $id)
            ->where('type', 'entree')
            ->whereNotNull('purchase_price')
            ->groupBy('purchase_price', 'reference_document')
            ->having('total_quantity', '>', 0)
            ->get();
        
        $totalStock = $product->stock ?? 0;
        $totalValue = $product->stock * $product->sale_price;
        $batchesCount = $stockByPrice->count();
        
        $totalValuePurchase = 0;
        foreach ($stockByPrice as $batch) {
            $totalValuePurchase += ($batch->total_quantity * $batch->purchase_price);
        }
        
        $averagePurchasePrice = $totalStock > 0 
            ? $totalValuePurchase / $totalStock 
            : 0;
        
        $profitPotential = $totalValue - $totalValuePurchase;
        
        $stockSummary = [
            'total_stock' => $totalStock,
            'total_value' => $totalValue,
            'average_purchase_price' => $averagePurchasePrice,
            'profit_potential' => $profitPotential,
            'batches_count' => $batchesCount,
            'has_multiple_batches' => $batchesCount > 1,
            'total_value_purchase' => $totalValuePurchase,
        ];
        
        $recentMovements = $product->stockMovements()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $calculatedStock = $product->stockMovements()
            ->where('type', 'entree')
            ->sum('quantity') 
            - $product->stockMovements()
                ->where('type', 'sortie')
                ->sum('quantity');
        
        $stockConsistency = [
            'current_stock' => $product->stock ?? 0,
            'calculated_stock' => $calculatedStock,
            'difference' => ($product->stock ?? 0) - $calculatedStock,
            'is_consistent' => ($product->stock ?? 0) == $calculatedStock
        ];
        
        return view('products.show', compact(
            'product',
            'stockByPrice',
            'stockSummary',
            'recentMovements',
            'stockConsistency'
        ));
    }

    // ✏️ Page d'édition
    public function edit(Product $product)
    {
        $this->authorizeProductAccess($product);
        $this->authorizeStockManagement();
        
        if (Schema::hasColumn('products', 'has_been_cumulated') && 
            $product->has_been_cumulated) {
            return redirect()->route('products.show', $product)
                ->with('warning', 'Ce produit a été cumulé et ne peut plus être modifié directement.');
        }
        
        $categories = Category::sameCompany()->get();
        $suppliers  = Supplier::sameCompany()->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    // ✏️ Mise à jour - CORRIGÉE (ne double plus le stock)
    public function update(Request $request, Product $product)
    {
        $this->authorizeProductAccess($product);
        $this->authorizeStockManagement();
        
        if (Schema::hasColumn('products', 'has_been_cumulated') && 
            $product->has_been_cumulated) {
            return redirect()->route('products.show', $product)
                ->with('warning', 'Ce produit a été cumulé et ne peut plus être modifié.');
        }
        
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'stock'          => 'required|integer|min:0',
            'stock_alert'    => 'nullable|integer|min:0',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
        ]);
        
        // Vérifier que la catégorie appartient à la même quincaillerie
        $category = Category::sameCompany()->find($request->category_id);
        if (!$category) {
            return back()->with('error', 'Catégorie invalide.');
        }

        // Vérifier que le fournisseur appartient à la même quincaillerie
        $supplier = Supplier::sameCompany()->find($request->supplier_id);
        if (!$supplier) {
            return back()->with('error', 'Fournisseur invalide.');
        }
        
        $oldStock = $product->stock;
        $newStock = $validated['stock'];
        $validated['quantity'] = $newStock;
        
        DB::beginTransaction();
        try {
            // Mettre à jour directement le stock
            $product->update($validated);
            
            // Enregistrer un mouvement pour tracer la modification (sans doubler)
            if ($oldStock != $newStock) {
                $difference = $newStock - $oldStock;
                $type = $difference > 0 ? 'entree' : 'sortie';
                $quantity = abs($difference);
                
                // Utiliser les prix actuels du produit
                $movement = StockMovement::create([
                    'product_id' => $product->id,
                    'type' => $type,
                    'quantity' => $quantity,
                    'purchase_price' => $validated['purchase_price'],
                    'sale_price' => $validated['sale_price'],
                    'stock_after' => $newStock, // On utilise directement la nouvelle valeur
                    'motif' => 'Modification manuelle du stock',
                    'reference_document' => 'EDIT-' . $product->id . '-' . time(),
                    'user_id' => auth()->id()
                ]);
                
                \Log::info('Stock modifié manuellement', [
                    'product_id' => $product->id,
                    'old_stock' => $oldStock,
                    'new_stock' => $newStock,
                    'difference' => $difference,
                    'user_id' => auth()->id()
                ]);
            }
            
            DB::commit();
            
            return redirect()->route('products.index')
                ->with('success', 'Produit mis à jour avec succès. Stock: ' . $product->fresh()->stock);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage())
                ->withInput();
        }
    }

    // 🗑️ Archive (soft delete) un produit
    public function destroy(Product $product)
    {
        $this->authorizeProductAccess($product);
        $this->authorizeStockManagement();

        $hasSales = $product->saleItems()->exists();

        // Soft delete : aucune violation FK possible
        $product->delete();

        $message = $hasSales
            ? "« {$product->name} » archivé (présent dans des ventes, données préservées)."
            : "Produit « {$product->name} » supprimé.";

        return redirect()->route('products.index')->with('success', $message);
    }

    // 📊 Rapport des produits
    public function productsReport(Request $request)
    {
        if (!Auth::user()->canViewReports()) {
            abort(403, 'Vous n\'avez pas les droits pour voir les rapports.');
        }
        
        $query = Product::query();
        
        if (Schema::hasColumn('products', 'has_been_cumulated')) {
            $query->where('has_been_cumulated', false);
        }
        
        // Filtres optionnels
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        $products = $query->with(['category', 'supplier'])
            ->orderBy('stock', 'asc')
            ->get();
        
        foreach ($products as $product) {
            $product->stock_totals = $product->getStockTotals();
        }
        
        $cumulatedProductsCount = 0;
        if (Schema::hasColumn('products', 'is_cumulated')) {
            $cumulatedProductsCount = Product::where('is_cumulated', true)->count();
        }
        
        $reportData = [
            'total_products' => $products->count(),
            'total_stock_value' => $products->sum(fn($p) => $p->stock * $p->purchase_price),
            'total_sale_value' => $products->sum(fn($p) => $p->stock * $p->sale_price),
            'low_stock' => $products->filter(fn($p) => $p->stock > 0 && $p->stock <= ($p->stock_alert ?? 10))->count(),
            'out_of_stock' => $products->where('stock', '=', 0)->count(),
            'total_purchased' => $products->sum('stock'),
            'products_multiple_batches' => $products->filter(fn($p) => $p->hasMultipleBatches())->count(),
            'total_batches' => $products->sum(fn($p) => $p->getStockTotals()['number_of_batches']),
            'cumulated_products' => $cumulatedProductsCount,
        ];

        // Données pour les filtres
        $categories = Category::sameCompany()->get();
        $suppliers = Supplier::sameCompany()->get();

        return view('reports.products', compact('products', 'reportData', 'categories', 'suppliers'));
    }

    // 📈 Statistiques rapides
    public function getQuickStats()
    {
        $query = Product::query();
        if (Schema::hasColumn('products', 'has_been_cumulated')) {
            $query->where('has_been_cumulated', false);
        }
        
        $products = $query->get();
        $productsWithMultipleBatches = $products->filter(fn($p) => $p->hasMultipleBatches())->count();
        $totalBatches = $products->sum(fn($p) => $p->getStockTotals()['number_of_batches']);
        
        $cumulatedProductsCount = 0;
        if (Schema::hasColumn('products', 'is_cumulated')) {
            $cumulatedProductsCount = Product::where('is_cumulated', true)->count();
        }
        
        return response()->json([
            'total_products' => $products->count(),
            'total_stock_value' => $products->sum(DB::raw('purchase_price * stock')),
            'total_sale_value' => $products->sum(DB::raw('sale_price * stock')),
            'low_stock_count' => $products->filter(fn($p) => $p->stock > 0 && $p->stock <= ($p->stock_alert ?? 10))->count(),
            'out_of_stock_count' => $products->where('stock', '=', 0)->count(),
            'total_stock' => $products->sum('stock'),
            'products_multiple_batches' => $productsWithMultipleBatches,
            'total_batches' => $totalBatches,
            'cumulated_products' => $cumulatedProductsCount,
        ]);
    }

    // ============================================
    // HISTORIQUE DES STOCKS
    // ============================================

    public function history(Product $product, Request $request)
    {
        $this->authorizeProductAccess($product);
        
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'type' => 'nullable|in:entree,sortie',
            'per_page' => 'nullable|integer|min:5|max:100'
        ]);
        
        $query = $product->stockMovements()
            ->with('user:id,name')
            ->orderBy('created_at', 'desc');
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        $perPage = $request->get('per_page', 20);
        $movements = $query->paginate($perPage);
        
        $totals = $product->stockMovements()
            ->selectRaw('type, SUM(quantity) as total_quantity, COUNT(*) as count')
            ->when($request->filled('start_date'), function($q) use ($request) {
                $q->whereDate('created_at', '>=', $request->start_date);
            })
            ->when($request->filled('end_date'), function($q) use ($request) {
                $q->whereDate('created_at', '<=', $request->end_date);
            })
            ->groupBy('type')
            ->get()
            ->keyBy('type');
        
        $stockTotals = $product->getStockTotals();
        
        return view('products.history', compact('product', 'movements', 'totals', 'stockTotals'));
    }
    
    public function globalHistory(Request $request)
    {
        if (!Auth::user()->canViewReports()) {
            abort(403, 'Vous n\'avez pas les droits pour voir l\'historique global.');
        }
        
        $request->validate([
            'product_id' => 'nullable|exists:products,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'type' => 'nullable|in:entree,sortie',
            'search' => 'nullable|string'
        ]);
        
        $query = StockMovement::with(['product:id,name', 'user:id,name'])
            ->orderBy('created_at', 'desc');
        
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }
        
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        
        if ($request->filled('search')) {
            $query->whereHas('product', function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%");
            });
        }
        
        $perPage = $request->get('per_page', 50);
        $movements = $query->paginate($perPage);
        
        $stats = StockMovement::selectRaw('
            COUNT(*) as total_movements,
            SUM(CASE WHEN type = "entree" THEN quantity ELSE 0 END) as total_entrees,
            SUM(CASE WHEN type = "sortie" THEN quantity ELSE 0 END) as total_sorties,
            AVG(purchase_price) as avg_purchase_price,
            AVG(sale_price) as avg_sale_price
        ')->when($request->filled('start_date'), function($q) use ($request) {
            $q->whereDate('created_at', '>=', $request->start_date);
        })
        ->when($request->filled('end_date'), function($q) use ($request) {
            $q->whereDate('created_at', '<=', $request->end_date);
        })->first();
        
        $products = Product::select('id', 'name')->get();
        
        return view('products.global-history', compact('movements', 'stats', 'products'));
    }
    
    public function groupedStocksReport(Request $request)
    {
        if (!Auth::user()->canViewReports()) {
            abort(403, 'Vous n\'avez pas les droits pour voir ce rapport.');
        }
        
        $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'sort_by' => 'nullable|in:name,total_value,batches_count'
        ]);
        
        $query = Product::query();
        
        if (Schema::hasColumn('products', 'has_been_cumulated')) {
            $query->where('has_been_cumulated', false);
        }
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        switch ($request->get('sort_by', 'name')) {
            case 'total_value':
                $query->orderByRaw('(sale_price * stock) DESC');
                break;
            case 'batches_count':
                break;
            default:
                $query->orderBy('name', 'asc');
                break;
        }
        
        $products = $query->get();
        
        $productsData = [];
        $totalGlobalValue = 0;
        $totalBatches = 0;
        
        foreach ($products as $product) {
            $stockTotals = $product->getStockTotals();
            $summary = $product->getStockSummary();
            
            $productsData[] = [
                'product' => $product,
                'summary' => $summary,
                'totals' => $stockTotals,
                'grouped_stocks' => $stockTotals['grouped_stocks'],
            ];
            
            $totalGlobalValue += $summary['total_value'];
            $totalBatches += $summary['batches_count'];
        }
        
        if ($request->get('sort_by') == 'batches_count') {
            usort($productsData, function($a, $b) {
                return $b['summary']['batches_count'] <=> $a['summary']['batches_count'];
            });
        }
        
        $cumulatedProducts = collect();
        if (Schema::hasColumn('products', 'is_cumulated')) {
            $cumulatedProducts = Product::where('is_cumulated', true)
                ->with(['stockMovements'])
                ->get();
        }
        
        $reportStats = [
            'total_products' => count($productsData),
            'total_cumulated_products' => $cumulatedProducts->count(),
            'total_value' => $totalGlobalValue,
            'total_batches' => $totalBatches,
            'products_with_multiple_batches' => collect($productsData)->filter(fn($p) => $p['summary']['has_multiple_batches'])->count(),
            'average_batches_per_product' => $totalBatches > 0 ? round($totalBatches / count($productsData), 1) : 0,
        ];
        
        $categories = Category::sameCompany()->get();
        $suppliers = Supplier::sameCompany()->get();
        
        return view('reports.grouped-stocks', compact(
            'productsData', 
            'cumulatedProducts',
            'reportStats', 
            'categories', 
            'suppliers'
        ));
    }
    
    // ============================================
    // MÉTHODES DE GESTION DU STOCK
    // ============================================
    
    private function addStockMovementWithPrice(Product $product, $type, $quantity, $purchase_price, $sale_price, $motif = null, $reference = null)
    {
        $this->authorizeProductAccess($product);
        $this->authorizeStockManagement();
        
        if ($type === 'sortie' && $product->stock < $quantity) {
            throw new \Exception("Stock insuffisant. Stock actuel: {$product->stock}");
        }
        
        $newStock = $type === 'entree' 
            ? $product->stock + $quantity 
            : $product->stock - $quantity;
        
        $movement = StockMovement::create([
            'product_id' => $product->id,
            'type' => $type,
            'quantity' => $quantity,
            'purchase_price' => $purchase_price,
            'sale_price' => $sale_price,
            'stock_after' => $newStock,
            'motif' => $motif,
            'reference_document' => $reference,
            'user_id' => auth()->id()
        ]);
        
        $product->update([
            'stock' => $newStock,
            'quantity' => $newStock
        ]);
        
        return $movement;
    }
    
    private function addStockMovement(Product $product, $type, $quantity, $motif = null, $reference = null)
    {
        return $this->addStockMovementWithPrice(
            $product,
            $type,
            $quantity,
            $product->purchase_price,
            $product->sale_price,
            $motif,
            $reference
        );
    }
    
    public function adjustStock(Request $request, Product $product)
    {
        $this->authorizeProductAccess($product);
        $this->authorizeStockManagement();
        
        if (Schema::hasColumn('products', 'has_been_cumulated') && $product->has_been_cumulated) {
            return redirect()->route('products.show', $product)
                ->with('warning', 'Ce produit a été cumulé et ne peut plus être modifié.');
        }
        
        $request->validate([
            'adjustment_type' => 'required|in:add,remove,set',
            'amount' => 'required|integer|min:0',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'reason' => 'nullable|string|max:500',
            'reference_document' => 'nullable|string|max:100'
        ]);
        
        try {
            DB::transaction(function () use ($request, $product) {
                $oldStock = $product->stock;
                $quantity = $request->amount;
                $type = 'entree';
                $motif = '';
                
                $purchase_price = $request->filled('purchase_price') 
                    ? $request->purchase_price 
                    : $product->purchase_price;
                    
                $sale_price = $request->filled('sale_price') 
                    ? $request->sale_price 
                    : $product->sale_price;
                
                switch ($request->adjustment_type) {
                    case 'add':
                        $type = 'entree';
                        $motif = 'Ajustement positif: ' . ($request->reason ?? '');
                        break;
                        
                    case 'remove':
                        if ($oldStock < $quantity) {
                            throw new \Exception("Stock insuffisant. Disponible: {$oldStock}, À retirer: {$quantity}");
                        }
                        $type = 'sortie';
                        $motif = 'Ajustement négatif: ' . ($request->reason ?? '');
                        break;
                        
                    case 'set':
                        $difference = $quantity - $oldStock;
                        if ($difference > 0) {
                            $type = 'entree';
                            $motif = 'Ajustement (définition stock): ' . ($request->reason ?? '');
                            $quantity = $difference;
                        } elseif ($difference < 0) {
                            $type = 'sortie';
                            $motif = 'Ajustement (définition stock): ' . ($request->reason ?? '');
                            $quantity = abs($difference);
                        } else {
                            if ($request->filled('purchase_price') || $request->filled('sale_price')) {
                                $updateData = [];
                                if ($request->filled('purchase_price')) {
                                    $updateData['purchase_price'] = $purchase_price;
                                }
                                if ($request->filled('sale_price')) {
                                    $updateData['sale_price'] = $sale_price;
                                }
                                $product->update($updateData);
                            }
                            return;
                        }
                        break;
                }
                
                $this->addStockMovementWithPrice(
                    $product,
                    $type,
                    $quantity,
                    $purchase_price,
                    $sale_price,
                    $motif,
                    $request->reference_document
                );
                
                if ($request->filled('purchase_price')) {
                    $product->update(['purchase_price' => $purchase_price]);
                }
                if ($request->filled('sale_price')) {
                    $product->update(['sale_price' => $sale_price]);
                }
                
                $product->refresh();
            });
            
            $product->refresh();
            
            return redirect()->route('products.index')
                ->with('success', "Stock ajusté avec succès. Stock actuel : {$product->stock}");
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }
    
    public function restock(Request $request, Product $product)
    {
        $this->authorizeProductAccess($product);
        $this->authorizeStockManagement();
        
        if (Schema::hasColumn('products', 'has_been_cumulated') && $product->has_been_cumulated) {
            return redirect()->route('products.show', $product)
                ->with('warning', 'Ce produit a été cumulé et ne peut plus être réapprovisionné.');
        }
        
        $request->validate([
            'amount' => 'required|integer|min:1',
            'purchase_price' => 'nullable|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'motif' => 'nullable|string|max:500',
            'reference_document' => 'nullable|string|max:100'
        ]);
        
        // Vérifier le fournisseur si fourni
        if ($request->filled('supplier_id')) {
            $supplier = Supplier::sameCompany()->find($request->supplier_id);
            if (!$supplier) {
                return back()->with('error', 'Fournisseur invalide.');
            }
        }
        
        DB::transaction(function () use ($request, $product) {
            $purchase_price = $request->filled('purchase_price') 
                ? $request->purchase_price 
                : $product->purchase_price;
                
            $sale_price = $request->filled('sale_price') 
                ? $request->sale_price 
                : $product->sale_price;
            
            $this->addStockMovementWithPrice(
                $product,
                'entree',
                $request->amount,
                $purchase_price,
                $sale_price,
                $request->motif ?? 'Réapprovisionnement',
                $request->reference_document
            );
            
            if ($request->filled('purchase_price')) {
                $product->update(['purchase_price' => $purchase_price]);
            }
            if ($request->filled('sale_price')) {
                $product->update(['sale_price' => $sale_price]);
            }
            
            if ($request->filled('supplier_id')) {
                $product->update(['supplier_id' => $request->supplier_id]);
            }
            
            $product->refresh();
        });
        
        return redirect()->route('products.index')
            ->with('success', "Réapprovisionnement réussi : +{$request->amount} unités. Stock actuel : {$product->stock}");
    }
    
    public function quickSale(Request $request, Product $product)
    {
        $this->authorizeProductAccess($product);
        
        if (!Auth::user()->canManageSales()) {
            abort(403, 'Vous n\'avez pas les droits pour effectuer des ventes.');
        }
        
        if (Schema::hasColumn('products', 'has_been_cumulated') && $product->has_been_cumulated) {
            $cumulatedProduct = Product::find($product->cumulated_to);
            if ($cumulatedProduct) {
                return redirect()->route('products.show', $cumulatedProduct)
                    ->with('warning', 'Ce produit a été cumulé. Veuillez effectuer la vente sur le produit cumulé.');
            }
        }
        
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'client_name' => 'nullable|string|max:255',
            'reference' => 'nullable|string|max:100'
        ]);
        
        DB::transaction(function () use ($request, $product) {
            $this->addStockMovement(
                $product,
                'sortie',
                $request->quantity,
                'Vente à ' . ($request->client_name ?? 'Client'),
                $request->reference
            );
            
            $product->refresh();
        });
        
        return redirect()->route('products.history', $product)
            ->with('success', "Vente enregistrée : -{$request->quantity} unités. Stock actuel : {$product->stock}");
    }
    
    public function uncumulateProduct(Product $product)
    {
        $this->authorizeProductAccess($product);
        $this->authorizeStockManagement();
        
        if (!Schema::hasColumn('products', 'is_cumulated') || !$product->is_cumulated) {
            return redirect()->back()
                ->with('error', 'Ce produit n\'est pas un produit cumulé.');
        }
        
        DB::beginTransaction();
        try {
            $originalProducts = collect();
            
            if (Schema::hasColumn('products', 'cumulated_to')) {
                $originalProducts = Product::where('cumulated_to', $product->id)->get();
            }
            
            if ($originalProducts->isEmpty() && Schema::hasColumn('products', 'parent_id')) {
                $originalProducts = Product::where('parent_id', $product->id)->get();
            }
            
            if ($originalProducts->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Aucun produit original trouvé pour ce cumul.');
            }
            
            foreach ($originalProducts as $original) {
                $originalStock = $original->getOriginal('stock') ?? 0;
                
                $updateData = ['stock' => $originalStock];
                
                if (Schema::hasColumn('products', 'has_been_cumulated')) {
                    $updateData['has_been_cumulated'] = false;
                }
                if (Schema::hasColumn('products', 'cumulated_to')) {
                    $updateData['cumulated_to'] = null;
                }
                
                $original->update($updateData);
                
                $this->addStockMovementWithPrice(
                    $original,
                    'entree',
                    $originalStock,
                    $original->purchase_price,
                    $original->sale_price,
                    'Restauration après dé-cumul',
                    'UNCUMUL-' . $product->id
                );
            }
            
            $product->delete();
            
            DB::commit();
            
            return redirect()->route('products.index')
                ->with('success', 'Cumul défait avec succès. Les produits originaux ont été restaurés.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors du dé-cumul: ' . $e->getMessage());
        }
    }
    
    public function mergeProducts(Request $request)
    {
        $this->authorizeStockManagement();
        
        $request->validate([
            'product_ids' => 'required|array|min:2',
            'product_ids.*' => 'exists:products,id',
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);
        
        // Vérifier la catégorie
        $category = Category::sameCompany()->find($request->category_id);
        if (!$category) {
            return back()->with('error', 'Catégorie invalide.');
        }
        
        // Vérifier le fournisseur
        $supplier = Supplier::sameCompany()->find($request->supplier_id);
        if (!$supplier) {
            return back()->with('error', 'Fournisseur invalide.');
        }
        
        DB::beginTransaction();
        try {
            $query = Product::whereIn('id', $request->product_ids);
            
            if (Schema::hasColumn('products', 'has_been_cumulated')) {
                $query->where('has_been_cumulated', false);
            }
            
            $products = $query->get();
            
            if ($products->count() < 2) {
                throw new \Exception('Sélectionnez au moins 2 produits non-cumulés à fusionner.');
            }
            
            // Vérifier que tous les produits appartiennent à la même quincaillerie
            foreach ($products as $product) {
                if (!Auth::user()->hasAccessTo($product)) {
                    throw new \Exception("Le produit {$product->name} ne vous appartient pas.");
                }
            }
            
            $totalStock = $products->sum('stock');
            $avgPurchasePrice = $products->avg('purchase_price');
            $avgSalePrice = $products->avg('sale_price');
            $totalQuantity = $products->sum('quantity');
            
            $cumulatedData = [
                'name' => $request->name,
                'stock' => $totalStock,
                'quantity' => $totalQuantity,
                'purchase_price' => $avgPurchasePrice,
                'sale_price' => $avgSalePrice,
                'description' => 'Produit fusionné de ' . $products->count() . ' produits',
                'category_id' => $request->category_id,
                'supplier_id' => $request->supplier_id,
                'batch_number' => $request->input('batch_reference', 'MERGE-' . time() . '-' . Str::random(4)),
            ];
            
            if (Schema::hasColumn('products', 'is_cumulated')) {
                $cumulatedData['is_cumulated'] = true;
            }
            
            $cumulatedProduct = Product::create($cumulatedData);
            
            foreach ($products as $product) {
                if ($product->stock > 0) {
                    $this->addStockMovementWithPrice(
                        $product,
                        'sortie',
                        $product->stock,
                        $product->purchase_price,
                        $product->sale_price,
                        'Transfert vers produit fusionné',
                        'MERGE-' . $cumulatedProduct->id
                    );
                    
                    $this->addStockMovementWithPrice(
                        $cumulatedProduct,
                        'entree',
                        $product->stock,
                        $product->purchase_price,
                        $product->sale_price,
                        'Ajout depuis ' . $product->name,
                        'FROM-' . $product->id
                    );
                }
                
                $updateData = ['stock' => 0, 'quantity' => 0];
                
                if (Schema::hasColumn('products', 'has_been_cumulated')) {
                    $updateData['has_been_cumulated'] = true;
                }
                
                if (Schema::hasColumn('products', 'cumulated_to')) {
                    $updateData['cumulated_to'] = $cumulatedProduct->id;
                }
                
                $product->update($updateData);
            }
            
            DB::commit();
            
            return redirect()->route('products.show', $cumulatedProduct)
                ->with('success', $products->count() . ' produits fusionnés avec succès dans un nouveau produit cumulé.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Erreur lors de la fusion: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function checkStockConsistency(Product $product)
    {
        $this->authorizeProductAccess($product);
        
        $totalEntrees = $product->stockMovements()
            ->where('type', 'entree')
            ->sum('quantity');
        
        $totalSorties = $product->stockMovements()
            ->where('type', 'sortie')
            ->sum('quantity');
        
        $calculatedStock = $totalEntrees - $totalSorties;
        $actualStock = $product->stock;
        
        return [
            'calculated' => $calculatedStock,
            'actual' => $actualStock,
            'difference' => $actualStock - $calculatedStock,
            'is_consistent' => $actualStock == $calculatedStock
        ];
    }
    
    public function syncStock(Product $product)
    {
        $this->authorizeProductAccess($product);
        $this->authorizeStockManagement();
        
        $consistency = $this->checkStockConsistency($product);
        
        if ($consistency['is_consistent']) {
            return redirect()->route('products.show', $product)
                ->with('info', 'Le stock est déjà cohérent.');
        }
        
        DB::transaction(function () use ($product, $consistency) {
            $oldStock = $product->stock;
            $newStock = $consistency['calculated'];
            
            $difference = $newStock - $oldStock;
            
            if ($difference != 0) {
                $type = $difference > 0 ? 'entree' : 'sortie';
                
                $this->addStockMovementWithPrice(
                    $product,
                    $type,
                    abs($difference),
                    $product->purchase_price,
                    $product->sale_price,
                    'Correction automatique de stock',
                    'SYNC-' . $product->id
                );
            }
        });
        
        return redirect()->route('products.show', $product)
            ->with('success', "Stock synchronisé : {$consistency['difference']} unités corrigées. Nouveau stock : {$product->refresh()->stock}");
    }

    /**
     * Afficher le rapport d'inventaire
     */
    public function inventoryReport(Request $request)
    {
        if (!Auth::user()->canViewReports()) {
            abort(403, 'Vous n\'avez pas les droits pour voir les rapports.');
        }
        
        $query = Product::with(['category', 'supplier']);
        
        // Appliquer le scope tenant
        if (method_exists(Product::class, 'sameCompany')) {
            $query->sameCompany();
        }
        
        // Filtres
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->whereColumn('stock', '<=', 'stock_alert')->where('stock', '>', 0);
                    break;
                case 'out':
                    $query->where('stock', 0);
                    break;
                case 'in':
                    $query->where('stock', '>', 0);
                    break;
            }
        }
        
        // Recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('id', 'LIKE', "%{$search}%");
            });
        }
        
        $products = $query->orderBy('name')->paginate(20);
        
        // Statistiques
        $statsQuery = clone $query;
        $stats = [
            'total_products' => $statsQuery->count(),
            'total_stock' => $statsQuery->sum('stock'),
            'total_value' => $statsQuery->get()->sum(function($p) {
                return ($p->purchase_price ?? 0) * ($p->stock ?? 0);
            }),
            'total_sale_value' => $statsQuery->get()->sum(function($p) {
                return ($p->sale_price ?? 0) * ($p->stock ?? 0);
            }),
            'low_stock_count' => (clone $query)->whereColumn('stock', '<=', 'stock_alert')->where('stock', '>', 0)->count(),
            'out_of_stock_count' => (clone $query)->where('stock', 0)->count(),
            'categories_count' => $products->pluck('category_id')->unique()->count(),
            'suppliers_count' => $products->pluck('supplier_id')->unique()->count(),
        ];
        
        // Données pour les filtres
        $categories = Category::sameCompany()->get();
        $suppliers = Supplier::sameCompany()->get();
        
        return view('reports.inventory', compact('products', 'stats', 'categories', 'suppliers'));
    }
}