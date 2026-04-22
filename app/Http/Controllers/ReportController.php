<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SaleItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('stock.manager');
    }

    /**
     * Page d'accueil des rapports
     */
    public function index()
    {
        return view('reports.index');
    }

    /**
     * Rapport des ventes
     */
    public function salesReport(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        // Période par défaut : mois actuel
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        // Ventes par jour
        $salesByDay = Sale::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total_sales'),
                DB::raw('SUM(final_price) as total_revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        // Top produits
        $topProducts = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.tenant_id', $tenantId)
            ->whereBetween('sales.created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_revenue', 'desc')
            ->limit(10)
            ->get();
        
        // Ventes par catégorie
        $salesByCategory = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->where('sales.tenant_id', $tenantId)
            ->whereBetween('sales.created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->select(
                'categories.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total_price) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get();
        
        // Statistiques globales
        $totalSales = Sale::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->count();
        
        $totalRevenue = Sale::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$startDate, Carbon::parse($endDate)->endOfDay()])
            ->sum('final_price');
        
        $averageSale = $totalSales > 0 ? $totalRevenue / $totalSales : 0;
        
        return view('reports.sales', compact(
            'salesByDay',
            'topProducts',
            'salesByCategory',
            'totalSales',
            'totalRevenue',
            'averageSale',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Rapport des produits
     */
    public function productsReport(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        // Top produits vendus (tous temps)
        $topSellingProducts = SaleItem::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->where('sales.tenant_id', $tenantId)
            ->select(
                'products.id',
                'products.name',
                'products.stock',
                'products.sale_price',
                DB::raw('SUM(sale_items.quantity) as total_sold'),
                DB::raw('SUM(sale_items.total_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.stock', 'products.sale_price')
            ->orderBy('total_sold', 'desc')
            ->limit(20)
            ->get();
        
        // Produits jamais vendus
        $neverSoldProducts = Product::where('tenant_id', $tenantId)
            ->whereDoesntHave('saleItems')
            ->get();
        
        // Produits par catégorie
        $productsByCategory = Category::whereHas('products', function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })
            ->withCount(['products' => function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            }])
            ->get();
        
        // Valeur du stock
        $totalStockValue = Product::where('tenant_id', $tenantId)
            ->sum(DB::raw('purchase_price * stock'));
        
        $totalProducts = Product::where('tenant_id', $tenantId)->count();
        
        return view('reports.products', compact(
            'topSellingProducts',
            'neverSoldProducts',
            'productsByCategory',
            'totalStockValue',
            'totalProducts'
        ));
    }

    /**
     * Rapport des clients
     */
    public function clientsReport(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        // Top clients
        $topClients = Client::where('tenant_id', $tenantId)
            ->withCount(['sales' => function($q) {
                $q->where('status', 'completed');
            }])
            ->withSum(['sales' => function($q) {
                $q->where('status', 'completed');
            }], 'final_price')
            ->orderBy('sales_sum_final_price', 'desc')
            ->limit(20)
            ->get();
        
        // Clients fidèles (plus de 5 achats)
        $loyalClients = Client::where('tenant_id', $tenantId)
            ->withCount('sales')
            ->having('sales_count', '>', 5)
            ->get();
        
        // Clients inactifs (pas d'achat depuis 30 jours)
        $inactiveClients = Client::where('tenant_id', $tenantId)
            ->whereDoesntHave('sales', function($q) {
                $q->where('created_at', '>', Carbon::now()->subDays(30));
            })
            ->get();
        
        $totalClients = Client::where('tenant_id', $tenantId)->count();
        
        return view('reports.clients', compact(
            'topClients',
            'loyalClients',
            'inactiveClients',
            'totalClients'
        ));
    }

    /**
     * Rapport d'inventaire
     */
    public function inventoryReport(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        // Stock par catégorie
        $stockByCategory = Category::with(['products' => function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            }])
            ->get()
            ->map(function($category) {
                return [
                    'name' => $category->name,
                    'total_products' => $category->products->count(),
                    'total_stock' => $category->products->sum('stock'),
                    'total_value' => $category->products->sum(function($p) {
                        return $p->purchase_price * $p->stock;
                    })
                ];
            });
        
        // Alertes stock
        $lowStockProducts = Product::where('tenant_id', $tenantId)
            ->where('stock', '<=', 5)
            ->orderBy('stock')
            ->get();
        
        $outOfStockProducts = Product::where('tenant_id', $tenantId)
            ->where('stock', 0)
            ->get();
        
        // Valeur totale du stock
        $totalStockValue = Product::where('tenant_id', $tenantId)
            ->sum(DB::raw('purchase_price * stock'));
        
        $totalProducts = Product::where('tenant_id', $tenantId)->count();
        
        return view('reports.inventory', compact(
            'stockByCategory',
            'lowStockProducts',
            'outOfStockProducts',
            'totalStockValue',
            'totalProducts'
        ));
    }

    /**
     * Rapport des stocks groupés
     */
    public function groupedStocksReport(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        $products = Product::where('tenant_id', $tenantId)
            ->with('category')
            ->orderBy('name')
            ->paginate(20);
        
        return view('reports.grouped-stocks', compact('products'));
    }

    /**
     * Export des stocks groupés
     */
    public function exportGroupedStocks(Request $request, $format = 'excel')
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;

        $plan = \App\Services\PlanService::for($user->tenant);
        if ($format === 'pdf' && !$plan->canExportPDF()) {
            return back()->with('upgrade', "L'export PDF est disponible à partir du plan Business (15 000 FCFA/mois).");
        }
        
        $products = Product::where('tenant_id', $tenantId)
            ->with('category')
            ->orderBy('name')
            ->get();
        
        if ($format === 'excel' || $format === 'csv') {
            // Logique d'export Excel/CSV
            return response()->json(['message' => 'Export ' . strtoupper($format) . ' en cours de développement']);
        } elseif ($format === 'pdf') {
            // Logique d'export PDF
            return response()->json(['message' => 'Export PDF en cours de développement']);
        }
        
        return back()->with('error', 'Format non supporté');
    }
}