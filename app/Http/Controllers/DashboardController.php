<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Vérifier que l'utilisateur a accès au dashboard
     */
    private function authorizeDashboardAccess()
    {
        // Tout utilisateur authentifié peut voir le dashboard
        // Mais les données sont filtrées par TenantScope
        return true;
    }

    public function index()
    {
        $this->authorizeDashboardAccess();
        
        $user = Auth::user();
        
        // 📅 Dates
        $today = Carbon::today();
        $weekAgo = Carbon::today()->subDays(7);

        // 1️⃣ Ventes aujourd'hui (filtrées par quincaillerie)
        $salesToday = Sale::whereDate('created_at', $today)->count();

        // 2️⃣ Chiffre d'affaires total (aujourd'hui)
        $totalRevenue = Sale::whereDate('created_at', $today)->sum('total_price');

        // 3️⃣ Chiffre d'affaires total (toutes les ventes)
        $totalRevenueAll = Sale::sum('total_price');

        // 4️⃣ Alertes Stock (basé sur le stock)
        $lowStockThreshold = 5;      // Produits à surveiller
        $criticalStockThreshold = 2; // Stock critique

        // Stock faible → <= 5 et > 2
        $lowStockProducts = Product::where('stock', '<=', $lowStockThreshold)
                                   ->where('stock', '>', $criticalStockThreshold)
                                   ->orderBy('stock')
                                   ->limit(10)
                                   ->get();

        // Stock critique → <= 2
        $criticalStockProducts = Product::where('stock', '<=', $criticalStockThreshold)
                                        ->orderBy('stock')
                                        ->limit(10)
                                        ->get();

        // Total alertes
        $lowStockCount = $lowStockProducts->count() + $criticalStockProducts->count();

        // 5️⃣ Clients actifs (mois en cours)
        $activeClients = Sale::whereMonth('created_at', $today->month)
                             ->distinct('client_id')
                             ->count('client_id');

        // 6️⃣ Nouveaux clients (7 jours)
        $newClients = Client::whereBetween('created_at', [$weekAgo, $today])->count();

        // 7️⃣ Ventes récentes
        $recentSales = Sale::with(['client', 'items.product'])
                           ->orderBy('created_at', 'desc')
                           ->take(10)
                           ->get();

        // 8️⃣ Données graphique (7 derniers jours)
        $dates = [];
        $totals = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates[] = $date->format('d/m');
            $totals[] = Sale::whereDate('created_at', $date)->sum('total_price') ?? 0;
        }

        // 9️⃣ Statistiques produits
        $totalProducts = Product::count();
        $totalStockValue = Product::sum(DB::raw('sale_price * stock'));

        // 1️⃣0️⃣ Statistiques fournisseurs
        $totalSuppliers = Supplier::count();
        $suppliersWithProducts = Supplier::has('products')->count();

        // 1️⃣1️⃣ Alerte ventes
        $dailyTarget = 5;
        $lowSalesAlert = $salesToday < $dailyTarget;

        // 1️⃣2️⃣ Informations utilisateur
        $userRole = $user->role;
        $isAdmin = $user->isSuperAdminOrAdmin();
        $employeesCount = $user->employees()->count();

        // Retour vue
        return view('dashboard', compact(
            'salesToday',
            'totalRevenue',
            'totalRevenueAll',
            'lowStockProducts',
            'criticalStockProducts',
            'lowStockCount',
            'activeClients',
            'newClients',
            'recentSales',
            'dates',
            'totals',
            'lowSalesAlert',
            'totalProducts',
            'totalStockValue',
            'totalSuppliers',
            'suppliersWithProducts',
            'userRole',
            'isAdmin',
            'employeesCount'
        ));
    }

    // 📊 Données du graphique en AJAX
    public function chartData(Request $request)
    {
        $this->authorizeDashboardAccess();
        
        $period = $request->get('period', 7);
        $dates = [];
        $totals = [];

        for ($i = $period - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates[] = $date->format('d/m');
            $totals[] = Sale::whereDate('created_at', $date)->sum('total_price') ?? 0;
        }

        return response()->json([
            'dates' => $dates,
            'totals' => $totals
        ]);
    }

    // 📌 Stats AJAX (cartes auto-refresh)
    public function stats()
    {
        $this->authorizeDashboardAccess();
        
        \Log::info('AJAX Stats called', [
            'authenticated' => auth()->check(),
            'user_id' => auth()->id(),
            'session_id' => session()->getId(),
            'ip' => request()->ip()
        ]);
        
        $today = Carbon::today();

        return response()->json([
            'salesToday' => Sale::whereDate('created_at', $today)->count(),
            'totalRevenue' => Sale::whereDate('created_at', $today)->sum('total_price'),
            'lowStockCount' => Product::where('stock', '<=', 5)->count(),
            'activeClients' => Sale::whereMonth('created_at', $today->month)
                                  ->distinct('client_id')
                                  ->count('client_id'),
            'averageSale' => Sale::whereDate('created_at', $today)->avg('total_price') ?? 0,
            'totalProducts' => Product::count(),
            'totalRevenueAll' => Sale::sum('total_price')
        ]);
    }

    // 🧾 Ventes récentes AJAX
    public function recentSales()
    {
        $this->authorizeDashboardAccess();
        
        $sales = Sale::with(['client', 'items.product'])
                    ->orderBy('created_at', 'desc')
                    ->take(10)
                    ->get()
                    ->map(function($sale) {
                        $productNames = $sale->items->map(function($item) {
                            return $item->product->name ?? 'Produit inconnu';
                        })->implode(', ');

                        return [
                            'id' => $sale->id,
                            'product_name' => $productNames,
                            'client_name' => $sale->client->name ?? 'Client inconnu',
                            'total_price' => $sale->total_price,
                            'formatted_total' => number_format($sale->total_price, 0, ',', ' ') . ' FCFA',
                            'created_at' => $sale->created_at->format('Y-m-d H:i:s'),
                            'formatted_date' => $sale->created_at->format('d/m/Y H:i')
                        ];
                    });

        return response()->json($sales);
    }

    // 🟥 Stock faible AJAX
    public function lowStock()
    {
        $this->authorizeDashboardAccess();
        
        $products = Product::where('stock', '<=', 5)
                        ->orderBy('stock')
                        ->get()
                        ->map(function($product) {
                            return [
                                'id' => $product->id,
                                'name' => $product->name,
                                'stock' => $product->stock,
                                'status' => $product->stock_status,
                                'sale_price' => $product->sale_price,
                                'formatted_price' => number_format($product->sale_price, 0, ',', ' ') . ' FCFA',
                                'profit_margin' => $product->profit_margin
                            ];
                        });

        return response()->json($products);
    }

    // 📈 Statistiques avancées (pour les rapports)
    public function advancedStats()
    {
        if (!Auth::user()->canViewReports()) {
            abort(403, 'Vous n\'avez pas les droits pour voir ces statistiques.');
        }
        
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $startOfYear = Carbon::now()->startOfYear();

        // Comparaison avec le mois dernier
        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Ventes du mois
        $salesThisMonth = Sale::whereBetween('created_at', [$startOfMonth, $today])->sum('total_price');
        $salesLastMonth = Sale::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('total_price');

        // Évolution en pourcentage
        $monthlyGrowth = $salesLastMonth > 0 
            ? round((($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100, 1) 
            : 100;

        // Top produits
        $topProducts = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->select(
                'products.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total_price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Top clients
        $topClients = DB::table('sales')
            ->join('clients', 'sales.client_id', '=', 'clients.id')
            ->select(
                'clients.name',
                DB::raw('COUNT(sales.id) as total_sales'),
                DB::raw('SUM(sales.total_price) as total_spent')
            )
            ->groupBy('clients.id', 'clients.name')
            ->orderBy('total_spent', 'desc')
            ->limit(5)
            ->get();

        // Distribution des stocks
        $stockDistribution = [
            'out_of_stock' => Product::where('stock', 0)->count(),
            'low_stock' => Product::where('stock', '>', 0)->where('stock', '<=', 5)->count(),
            'medium_stock' => Product::where('stock', '>', 5)->where('stock', '<=', 20)->count(),
            'high_stock' => Product::where('stock', '>', 20)->count(),
        ];

        return response()->json([
            'sales_this_month' => $salesThisMonth,
            'formatted_sales_this_month' => number_format($salesThisMonth, 0, ',', ' ') . ' FCFA',
            'sales_last_month' => $salesLastMonth,
            'monthly_growth' => $monthlyGrowth,
            'top_products' => $topProducts,
            'top_clients' => $topClients,
            'stock_distribution' => $stockDistribution,
            'total_products' => Product::count(),
            'total_clients' => Client::count(),
            'total_suppliers' => Supplier::count(),
        ]);
    }

    // 📊 Données pour le graphique des ventes par catégorie
    public function salesByCategory()
    {
        if (!Auth::user()->canViewReports()) {
            abort(403);
        }
        
        $data = DB::table('sale_items')
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total_price) as total_revenue')
            )
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_revenue', 'desc')
            ->get();

        return response()->json($data);
    }

    // 👥 Informations sur les employés (pour super_admin et admin)
    public function employeeStats()
    {
        if (!Auth::user()->canManageUsers()) {
            abort(403);
        }
        
        $user = Auth::user();
        
        $employees = $user->employees()
            ->withCount('sales')
            ->withSum('sales', 'total_price')
            ->get();

        $stats = [
            'total_employees' => $employees->count(),
            'total_sales_by_employees' => $employees->sum('sales_count'),
            'total_revenue_by_employees' => $employees->sum('sales_sum_total_price'),
            'best_employee' => $employees->sortByDesc('sales_sum_total_price')->first(),
            'employees_by_role' => $employees->groupBy('role')->map->count(),
        ];

        return response()->json($stats);
    }

    // 🔔 Notifications (alertes importantes)
    public function notifications()
    {
        $this->authorizeDashboardAccess();
        
        $notifications = [];
        
        // Alertes stock critique
        $criticalStock = Product::where('stock', '<=', 2)->count();
        if ($criticalStock > 0) {
            $notifications[] = [
                'type' => 'danger',
                'icon' => 'exclamation-triangle',
                'message' => "{$criticalStock} produit(s) en stock critique.",
                'link' => route('products.index', ['filter' => 'low_stock'])
            ];
        }
        
        // Alertes stock faible
        $lowStock = Product::where('stock', '>', 2)->where('stock', '<=', 5)->count();
        if ($lowStock > 0) {
            $notifications[] = [
                'type' => 'warning',
                'icon' => 'exclamation-circle',
                'message' => "{$lowStock} produit(s) en stock faible.",
                'link' => route('products.index', ['filter' => 'low_stock'])
            ];
        }
        
        // Nouvelles ventes aujourd'hui
        $salesToday = Sale::whereDate('created_at', Carbon::today())->count();
        if ($salesToday > 0) {
            $notifications[] = [
                'type' => 'success',
                'icon' => 'cart-check',
                'message' => "{$salesToday} vente(s) aujourd'hui.",
                'link' => route('sales.index', ['date' => Carbon::today()->format('Y-m-d')])
            ];
        }
        
        // Nouveaux clients cette semaine
        $newClients = Client::whereBetween('created_at', [Carbon::today()->subDays(7), Carbon::today()])->count();
        if ($newClients > 0) {
            $notifications[] = [
                'type' => 'info',
                'icon' => 'person-plus',
                'message' => "{$newClients} nouveau(x) client(s) cette semaine.",
                'link' => route('clients.index')
            ];
        }
        
        return response()->json($notifications);
    }
}