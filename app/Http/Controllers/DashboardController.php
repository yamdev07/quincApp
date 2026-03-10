<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\User;
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
        return true;
    }

    public function index()
    {
        $this->authorizeDashboardAccess();
        
        $user = Auth::user();
        
        // 👇 Si c'est le super_admin_global (toi), afficher le dashboard global
        if ($user->isSuperAdminGlobal()) {
            return $this->globalDashboard();
        }
        
        // 👇 Sinon, dashboard normal pour les quincailleries
        return $this->tenantDashboard();
    }

    /**
     * Dashboard pour les utilisateurs d'une quincaillerie
     */
    private function tenantDashboard()
    {
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
        $lowStockThreshold = 5;
        $criticalStockThreshold = 2;

        $lowStockProducts = Product::where('stock', '<=', $lowStockThreshold)
                                   ->where('stock', '>', $criticalStockThreshold)
                                   ->orderBy('stock')
                                   ->limit(10)
                                   ->get();

        $criticalStockProducts = Product::where('stock', '<=', $criticalStockThreshold)
                                        ->orderBy('stock')
                                        ->limit(10)
                                        ->get();

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

    /**
     * Dashboard GLOBAL pour toi (super_admin_global)
     */
    private function globalDashboard()
    {
        // 📊 Statistiques globales
        $totalTenants = Tenant::count();
        $activeTenants = Tenant::where('is_active', true)->count();
        $inactiveTenants = Tenant::where('is_active', false)->count();
        $expiringSoon = Tenant::where('is_active', true)
                             ->where('subscription_ends_at', '<=', now()->addDays(30))
                             ->where('subscription_ends_at', '>', now())
                             ->count();
        
        // 👥 Statistiques utilisateurs
        $totalUsers = User::count();
        $totalSuperAdmins = User::where('role', 'super_admin')->count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalManagers = User::where('role', 'manager')->count();
        $totalCashiers = User::where('role', 'cashier')->count();
        $totalStorekeepers = User::where('role', 'storekeeper')->count();
        
        // 📈 Nouveaux tenants
        $newTenantsToday = Tenant::whereDate('created_at', Carbon::today())->count();
        $newTenantsThisWeek = Tenant::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $newTenantsThisMonth = Tenant::whereMonth('created_at', now()->month)
                                    ->whereYear('created_at', now()->year)
                                    ->count();
        
        // 💰 Chiffre d'affaires global
        $totalRevenueAllTime = Sale::sum('total_price');
        $totalRevenueToday = Sale::whereDate('created_at', Carbon::today())->sum('total_price');
        $totalRevenueThisMonth = Sale::whereMonth('created_at', now()->month)
                                     ->whereYear('created_at', now()->year)
                                     ->sum('total_price');
        
        // 📊 Graphique des inscriptions (12 derniers mois)
        $months = [];
        $registrations = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            $registrations[] = Tenant::whereYear('created_at', $date->year)
                                     ->whereMonth('created_at', $date->month)
                                     ->count();
        }
        
        // 📊 Graphique du CA global (30 derniers jours)
        $chartDates = [];
        $chartRevenues = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $chartDates[] = $date->format('d/m');
            $chartRevenues[] = Sale::whereDate('created_at', $date)->sum('total_price') ?? 0;
        }
        
        // 🏆 Top 5 tenants par CA
        $topTenants = Tenant::withCount('users')
                           ->withSum('sales', 'total_price')
                           ->orderBy('sales_sum_total_price', 'desc')
                           ->limit(5)
                           ->get();
        
        // 📦 Statistiques globales produits
        $totalProducts = Product::count();
        $totalStockValue = Product::sum(DB::raw('purchase_price * stock'));
        $outOfStock = Product::where('stock', 0)->count();
        
        return view('dashboard-global', compact(
            'totalTenants',
            'activeTenants',
            'inactiveTenants',
            'expiringSoon',
            'totalUsers',
            'totalSuperAdmins',
            'totalAdmins',
            'totalManagers',
            'totalCashiers',
            'totalStorekeepers',
            'newTenantsToday',
            'newTenantsThisWeek',
            'newTenantsThisMonth',
            'totalRevenueAllTime',
            'totalRevenueToday',
            'totalRevenueThisMonth',
            'months',
            'registrations',
            'chartDates',
            'chartRevenues',
            'topTenants',
            'totalProducts',
            'totalStockValue',
            'outOfStock'
        ));
    }

    // 📊 Données du graphique en AJAX
    public function chartData(Request $request)
    {
        $this->authorizeDashboardAccess();
        
        $user = Auth::user();
        $period = $request->get('period', 7);
        $dates = [];
        $totals = [];

        for ($i = $period - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates[] = $date->format('d/m');
            
            // Si super_admin_global, voir TOUTES les ventes
            if ($user->isSuperAdminGlobal()) {
                $totals[] = Sale::whereDate('created_at', $date)->sum('total_price') ?? 0;
            } else {
                $totals[] = Sale::whereDate('created_at', $date)->sum('total_price') ?? 0;
            }
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
        
        $user = Auth::user();
        $today = Carbon::today();

        // Si super_admin_global, stats globales
        if ($user->isSuperAdminGlobal()) {
            return response()->json([
                'salesToday' => Sale::whereDate('created_at', $today)->count(),
                'totalRevenue' => Sale::whereDate('created_at', $today)->sum('total_price'),
                'lowStockCount' => Product::where('stock', '<=', 5)->count(),
                'activeTenants' => Tenant::where('is_active', true)->count(),
                'totalTenants' => Tenant::count(),
                'averageSale' => Sale::whereDate('created_at', $today)->avg('total_price') ?? 0,
                'totalProducts' => Product::count(),
                'totalRevenueAll' => Sale::sum('total_price')
            ]);
        }

        // Sinon, stats de la quincaillerie
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

        $lastMonth = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        $salesThisMonth = Sale::whereBetween('created_at', [$startOfMonth, $today])->sum('total_price');
        $salesLastMonth = Sale::whereBetween('created_at', [$lastMonth, $lastMonthEnd])->sum('total_price');

        $monthlyGrowth = $salesLastMonth > 0 
            ? round((($salesThisMonth - $salesLastMonth) / $salesLastMonth) * 100, 1) 
            : 100;

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
        
        $user = Auth::user();
        $notifications = [];
        
        // Si super_admin_global, alertes globales
        if ($user->isSuperAdminGlobal()) {
            $expiringTenants = Tenant::where('is_active', true)
                                   ->where('subscription_ends_at', '<=', now()->addDays(7))
                                   ->where('subscription_ends_at', '>', now())
                                   ->count();
            if ($expiringTenants > 0) {
                $notifications[] = [
                    'type' => 'warning',
                    'icon' => 'calendar',
                    'message' => "{$expiringTenants} abonnement(s) expirent dans moins de 7 jours.",
                    'link' => route('super-admin.tenants')
                ];
            }
            
            $newTenants = Tenant::whereDate('created_at', Carbon::today())->count();
            if ($newTenants > 0) {
                $notifications[] = [
                    'type' => 'success',
                    'icon' => 'building',
                    'message' => "{$newTenants} nouvelle(s) quincaillerie(s) aujourd'hui.",
                    'link' => route('super-admin.tenants')
                ];
            }
        }
        
        // Alertes stock critique (pour tous)
        $criticalStock = Product::where('stock', '<=', 2)->count();
        if ($criticalStock > 0) {
            $notifications[] = [
                'type' => 'danger',
                'icon' => 'exclamation-triangle',
                'message' => "{$criticalStock} produit(s) en stock critique.",
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
                'link' => route('sales.index')
            ];
        }
        
        return response()->json($notifications);
    }
}