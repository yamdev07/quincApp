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

    public function index(\App\Services\DashboardService $dashboard)
    {
        $user = Auth::user();

        if ($user->isSuperAdminGlobal()) {
            return $this->globalDashboard($dashboard);
        }

        return $this->tenantDashboard($dashboard, $user);
    }

    private function tenantDashboard(\App\Services\DashboardService $dashboard, $user)
    {
        $s = $dashboard->tenantStats($user->tenant_id);

        return view('dashboard', [
            'salesToday'             => $s['sales_today'],
            'totalRevenue'           => $s['revenue_today'],
            'totalRevenueAll'        => $s['revenue_all'],
            'lowStockProducts'       => $s['low_stock_products'],
            'criticalStockProducts'  => $s['critical_stock_products'],
            'lowStockCount'          => $s['low_stock_count'],
            'activeClients'          => $s['active_clients'],
            'newClients'             => $s['new_clients'],
            'recentSales'            => $s['recent_sales'],
            'dates'                  => $s['chart_dates'],
            'totals'                 => $s['chart_totals'],
            'lowSalesAlert'          => $s['low_sales_alert'],
            'totalProducts'          => $s['total_products'],
            'totalStockValue'        => $s['total_stock_value'],
            'totalSuppliers'         => $s['total_suppliers'],
            'suppliersWithProducts'  => Supplier::where('tenant_id', $user->tenant_id)->has('products')->count(),
            'userRole'               => $user->role,
            'isAdmin'                => $user->isSuperAdminOrAdmin(),
            'employeesCount'         => $s['employees_count'],
        ]);
    }

    private function globalDashboard(\App\Services\DashboardService $dashboard)
    {
        $s = $dashboard->globalStats();

        return view('dashboard-global', [
            'totalTenants'          => $s['total_tenants'],
            'activeTenants'         => $s['active_tenants'],
            'inactiveTenants'       => $s['inactive_tenants'],
            'expiringSoon'          => $s['expiring_soon'],
            'totalUsers'            => $s['total_users'],
            'totalSuperAdmins'      => $s['users_by_role']['super_admin'] ?? 0,
            'totalAdmins'           => $s['users_by_role']['admin'] ?? 0,
            'totalManagers'         => $s['users_by_role']['manager'] ?? 0,
            'totalCashiers'         => $s['users_by_role']['cashier'] ?? 0,
            'totalStorekeepers'     => $s['users_by_role']['storekeeper'] ?? 0,
            'newTenantsToday'       => $s['new_tenants_today'],
            'newTenantsThisWeek'    => $s['new_tenants_week'],
            'newTenantsThisMonth'   => $s['new_tenants_month'],
            'totalRevenueAllTime'   => $s['revenue_all_time'],
            'totalRevenueToday'     => $s['revenue_today'],
            'totalRevenueThisMonth' => $s['revenue_this_month'],
            'months'                => $s['months'],
            'registrations'         => $s['registrations'],
            'chartDates'            => $s['chart_dates'],
            'chartRevenues'         => $s['chart_revenues'],
            'topTenants'            => $s['top_tenants'],
            'totalProducts'         => $s['total_products'],
            'totalStockValue'       => $s['total_stock_value'],
            'outOfStock'            => $s['out_of_stock'],
        ]);
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
                'lowStockCount' => Product::whereColumn('stock', '<=', 'stock_alert')->count(),
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
        
        $products = Product::whereColumn('stock', '<=', 'stock_alert')
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
            'low_stock' => Product::where('stock', '>', 0)->whereColumn('stock', '<=', 'stock_alert')->count(),
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

    /**
     * API pour les statistiques du dashboard (utilisée par la page des rapports)
     */
    public function dashboardStats(Request $request)
    {
        $user = Auth::user();
        $tenantId = $user->tenant_id;
        
        // Si super_admin_global, stats globales
        if ($user->isSuperAdminGlobal()) {
            return response()->json([
                'total_sales' => Sale::count(),
                'total_revenue' => Sale::sum('total_price'),
                'total_quantity_sold' => SaleItem::sum('quantity'),
                'low_stock_count' => Product::where('stock', '<=', 5)->count(),
            ]);
        }
        
        // Sinon, stats du tenant
        return response()->json([
            'total_sales' => Sale::where('tenant_id', $tenantId)->count(),
            'total_revenue' => Sale::where('tenant_id', $tenantId)->sum('final_price'),
            'total_quantity_sold' => SaleItem::whereHas('sale', function($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId);
            })->sum('quantity'),
            'low_stock_count' => Product::where('tenant_id', $tenantId)
                ->whereColumn('stock', '<=', 'stock_alert')
                ->count(),
        ]);
    }
}