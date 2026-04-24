<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use App\Policies\ProductPolicy;
use App\Policies\SalePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Injection des services dans le container IoC
        $this->app->singleton(\App\Services\ProductService::class);
        $this->app->singleton(\App\Services\SaleService::class);
        $this->app->singleton(\App\Services\DashboardService::class);
        $this->app->singleton(\App\Services\PlanService::class);
    }

    public function boot(): void
    {
        // Policies
        Gate::policy(Product::class, ProductPolicy::class);
        Gate::policy(Sale::class, SalePolicy::class);
        Gate::policy(User::class, UserPolicy::class);

        // Gate globale pour le super admin
        Gate::before(function (User $user, string $ability) {
            if ($user->isSuperAdminGlobal()) {
                return true;
            }
        });
    }
}
