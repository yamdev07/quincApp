<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\Product;
use App\Models\Client;
use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DashboardService
{
    /**
     * Toutes les stats du dashboard tenant en UN SEUL passage.
     */
    public function tenantStats(int $tenantId): array
    {
        // Cache 5 min — invalidé automatiquement à minuit (today change)
        return Cache::remember("dashboard.tenant.{$tenantId}." . Carbon::today()->toDateString(), 300, function () use ($tenantId) {
            return $this->buildTenantStats($tenantId);
        });
    }

    public function invalidateTenantCache(int $tenantId): void
    {
        Cache::forget("dashboard.tenant.{$tenantId}." . Carbon::today()->toDateString());
    }

    private function buildTenantStats(int $tenantId): array
    {
        $today   = Carbon::today();
        $weekAgo = Carbon::today()->subDays(7);

        // --- Ventes et CA en une query agrégée ---
        $salesAgg = Sale::where('tenant_id', $tenantId)
            ->selectRaw("
                COUNT(*) as total_count,
                SUM(total_price) as total_revenue,
                SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today_count,
                SUM(CASE WHEN DATE(created_at) = ? THEN total_price ELSE 0 END) as today_revenue,
                COUNT(DISTINCT CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN client_id END) as active_clients
            ", [$today->toDateString(), $today->toDateString(), $today->month, $today->year])
            ->first();

        // --- Produits en une query agrégée ---
        $productsAgg = Product::where('tenant_id', $tenantId)
            ->selectRaw("
                COUNT(*) as total,
                SUM(sale_price * stock) as stock_value,
                SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as out_of_stock,
                SUM(CASE WHEN stock > 0 AND stock <= stock_alert THEN 1 ELSE 0 END) as low_stock
            ")
            ->first();

        // --- Alertes stock avec détail (10 max) ---
        $criticalThreshold = 2;
        $lowStockProducts = Product::where('tenant_id', $tenantId)
            ->whereColumn('stock', '<=', 'stock_alert')
            ->where('stock', '>', $criticalThreshold)
            ->orderBy('stock')
            ->limit(10)
            ->get(['id', 'name', 'stock', 'stock_alert']);

        $criticalStockProducts = Product::where('tenant_id', $tenantId)
            ->where('stock', '<=', $criticalThreshold)
            ->orderBy('stock')
            ->limit(10)
            ->get(['id', 'name', 'stock', 'stock_alert']);

        // --- Ventes récentes ---
        $recentSales = Sale::with(['client:id,name', 'items'])
            ->where('tenant_id', $tenantId)
            ->latest()
            ->limit(10)
            ->get();

        // --- Données graphique 7 jours en UNE query ---
        $chartData = Sale::where('tenant_id', $tenantId)
            ->where('created_at', '>=', Carbon::today()->subDays(6)->startOfDay())
            ->selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $dates  = [];
        $totals = [];
        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $dates[]  = $d->format('d/m');
            $totals[] = $chartData[$d->toDateString()] ?? 0;
        }

        // --- Nouveaux clients ---
        $newClients = Client::where('tenant_id', $tenantId)
            ->whereBetween('created_at', [$weekAgo, $today])
            ->count();

        // --- Fournisseurs ---
        $totalSuppliers = Supplier::where('tenant_id', $tenantId)->count();

        // --- Employés ---
        $employeesCount = User::where('tenant_id', $tenantId)
            ->where('role', '!=', 'super_admin')
            ->count();

        return [
            'sales_today'             => $salesAgg->today_count ?? 0,
            'revenue_today'            => $salesAgg->today_revenue ?? 0,
            'revenue_all'              => $salesAgg->total_revenue ?? 0,
            'active_clients'           => $salesAgg->active_clients ?? 0,
            'new_clients'              => $newClients,
            'total_products'           => $productsAgg->total ?? 0,
            'total_stock_value'        => $productsAgg->stock_value ?? 0,
            'low_stock_count'          => ($productsAgg->low_stock ?? 0) + ($productsAgg->out_of_stock ?? 0),
            'low_stock_products'       => $lowStockProducts,
            'critical_stock_products'  => $criticalStockProducts,
            'recent_sales'             => $recentSales,
            'chart_dates'              => $dates,
            'chart_totals'             => $totals,
            'total_suppliers'          => $totalSuppliers,
            'employees_count'          => $employeesCount,
            'low_sales_alert'          => ($salesAgg->today_count ?? 0) < 5,
        ];
    }

    /**
     * Stats globales pour le super admin.
     */
    public function globalStats(): array
    {
        // Tenants
        $tenantsAgg = Tenant::selectRaw("
            COUNT(*) as total,
            SUM(is_active) as active,
            SUM(CASE WHEN is_active = 0 THEN 1 ELSE 0 END) as inactive,
            SUM(CASE WHEN is_active = 1 AND subscription_ends_at <= ? AND subscription_ends_at > NOW() THEN 1 ELSE 0 END) as expiring_soon
        ", [now()->addDays(30)])->first();

        // Users par rôle
        $usersByRole = User::selectRaw("role, COUNT(*) as count")
            ->whereNotNull('tenant_id')
            ->groupBy('role')
            ->pluck('count', 'role');

        // Nouveaux tenants
        $newTenants = Tenant::selectRaw("
            SUM(CASE WHEN DATE(created_at) = ? THEN 1 ELSE 0 END) as today,
            SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as this_week,
            SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN 1 ELSE 0 END) as this_month
        ", [now()->toDateString(), now()->startOfWeek(), now()->month, now()->year])->first();

        // CA global
        $revenueAgg = Sale::selectRaw("
            SUM(total_price) as all_time,
            SUM(CASE WHEN DATE(created_at) = ? THEN total_price ELSE 0 END) as today,
            SUM(CASE WHEN MONTH(created_at) = ? AND YEAR(created_at) = ? THEN total_price ELSE 0 END) as this_month
        ", [now()->toDateString(), now()->month, now()->year])->first();

        // CA global 30 jours (une query)
        $revenueChart = Sale::selectRaw('DATE(created_at) as date, SUM(total_price) as total')
            ->where('created_at', '>=', Carbon::today()->subDays(29)->startOfDay())
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        $chartDates = [];
        $chartRevenues = [];
        for ($i = 29; $i >= 0; $i--) {
            $d = Carbon::today()->subDays($i);
            $chartDates[]   = $d->format('d/m');
            $chartRevenues[] = $revenueChart[$d->toDateString()] ?? 0;
        }

        // Inscriptions 12 mois (une query)
        $regChart = Tenant::selectRaw('YEAR(created_at) as yr, MONTH(created_at) as mo, COUNT(*) as count')
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy('yr', 'mo')
            ->orderBy('yr')->orderBy('mo')
            ->get()
            ->keyBy(fn($r) => "{$r->yr}-{$r->mo}");

        $months = [];
        $registrations = [];
        for ($i = 11; $i >= 0; $i--) {
            $d = now()->subMonths($i);
            $months[]        = $d->format('M Y');
            $registrations[] = $regChart["{$d->year}-{$d->month}"]->count ?? 0;
        }

        // Top 5 tenants
        $topTenants = Tenant::withCount('users')
            ->withSum('sales', 'total_price')
            ->orderBy('sales_sum_total_price', 'desc')
            ->limit(5)
            ->get();

        // Produits et stock globaux
        $productsAgg = Product::selectRaw("
            COUNT(*) as total,
            SUM(purchase_price * stock) as stock_value,
            SUM(CASE WHEN stock = 0 THEN 1 ELSE 0 END) as out_of_stock
        ")->first();

        return [
            'total_tenants'      => $tenantsAgg->total ?? 0,
            'active_tenants'     => $tenantsAgg->active ?? 0,
            'inactive_tenants'   => $tenantsAgg->inactive ?? 0,
            'expiring_soon'      => $tenantsAgg->expiring_soon ?? 0,
            'total_users'        => User::whereNotNull('tenant_id')->count(),
            'users_by_role'      => $usersByRole,
            'new_tenants_today'  => $newTenants->today ?? 0,
            'new_tenants_week'   => $newTenants->this_week ?? 0,
            'new_tenants_month'  => $newTenants->this_month ?? 0,
            'revenue_all_time'   => $revenueAgg->all_time ?? 0,
            'revenue_today'      => $revenueAgg->today ?? 0,
            'revenue_this_month' => $revenueAgg->this_month ?? 0,
            'chart_dates'        => $chartDates,
            'chart_revenues'     => $chartRevenues,
            'months'             => $months,
            'registrations'      => $registrations,
            'top_tenants'        => $topTenants,
            'total_products'     => $productsAgg->total ?? 0,
            'total_stock_value'  => $productsAgg->stock_value ?? 0,
            'out_of_stock'       => $productsAgg->out_of_stock ?? 0,
        ];
    }
}
