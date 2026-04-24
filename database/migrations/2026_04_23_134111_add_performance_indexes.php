<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    private function hasIndex(string $table, string $index): bool
    {
        try {
            $results = DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index]);
            return count($results) > 0;
        } catch (\Exception) {
            return false;
        }
    }

    public function up(): void
    {
        // --- products ---
        Schema::table('products', function (Blueprint $table) {
            if (!$this->hasIndex('products', 'products_tenant_id_index'))
                $table->index('tenant_id', 'products_tenant_id_index');
            if (!$this->hasIndex('products', 'products_owner_id_index'))
                $table->index('owner_id', 'products_owner_id_index');
            if (!$this->hasIndex('products', 'products_category_id_index'))
                $table->index('category_id', 'products_category_id_index');
            if (!$this->hasIndex('products', 'products_supplier_id_index'))
                $table->index('supplier_id', 'products_supplier_id_index');
            if (!$this->hasIndex('products', 'products_stock_index'))
                $table->index('stock', 'products_stock_index');
            if (!$this->hasIndex('products', 'products_created_at_index'))
                $table->index('created_at', 'products_created_at_index');
        });

        // --- sales ---
        Schema::table('sales', function (Blueprint $table) {
            if (!$this->hasIndex('sales', 'sales_tenant_id_index'))
                $table->index('tenant_id', 'sales_tenant_id_index');
            if (!$this->hasIndex('sales', 'sales_client_id_index'))
                $table->index('client_id', 'sales_client_id_index');
            if (!$this->hasIndex('sales', 'sales_user_id_index'))
                $table->index('user_id', 'sales_user_id_index');
            if (!$this->hasIndex('sales', 'sales_created_at_index'))
                $table->index('created_at', 'sales_created_at_index');
            // status column only if it exists
            if (Schema::hasColumn('sales', 'status') && !$this->hasIndex('sales', 'sales_status_index'))
                $table->index('status', 'sales_status_index');
        });

        // --- sale_items ---
        Schema::table('sale_items', function (Blueprint $table) {
            if (!$this->hasIndex('sale_items', 'sale_items_sale_id_index'))
                $table->index('sale_id', 'sale_items_sale_id_index');
            if (!$this->hasIndex('sale_items', 'sale_items_product_id_index'))
                $table->index('product_id', 'sale_items_product_id_index');
            if (!$this->hasIndex('sale_items', 'sale_items_tenant_id_index'))
                $table->index('tenant_id', 'sale_items_tenant_id_index');
        });

        // --- stock_movements ---
        Schema::table('stock_movements', function (Blueprint $table) {
            if (!$this->hasIndex('stock_movements', 'sm_product_id_index'))
                $table->index('product_id', 'sm_product_id_index');
            if (!$this->hasIndex('stock_movements', 'sm_tenant_id_index'))
                $table->index('tenant_id', 'sm_tenant_id_index');
            if (!$this->hasIndex('stock_movements', 'sm_type_index'))
                $table->index('type', 'sm_type_index');
            if (!$this->hasIndex('stock_movements', 'sm_created_at_index'))
                $table->index('created_at', 'sm_created_at_index');
        });

        // --- clients ---
        Schema::table('clients', function (Blueprint $table) {
            if (!$this->hasIndex('clients', 'clients_tenant_id_index'))
                $table->index('tenant_id', 'clients_tenant_id_index');
            if (!$this->hasIndex('clients', 'clients_created_at_index'))
                $table->index('created_at', 'clients_created_at_index');
        });

        // --- users ---
        Schema::table('users', function (Blueprint $table) {
            if (!$this->hasIndex('users', 'users_tenant_id_index'))
                $table->index('tenant_id', 'users_tenant_id_index');
            if (!$this->hasIndex('users', 'users_role_index'))
                $table->index('role', 'users_role_index');
        });

        // --- tenants ---
        Schema::table('tenants', function (Blueprint $table) {
            if (!$this->hasIndex('tenants', 'tenants_is_active_index'))
                $table->index('is_active', 'tenants_is_active_index');
            if (!$this->hasIndex('tenants', 'tenants_subscription_ends_at_index'))
                $table->index('subscription_ends_at', 'tenants_subscription_ends_at_index');
        });
    }

    public function down(): void
    {
        Schema::table('products',        fn($t) => $this->dropSafe($t, ['products_tenant_id_index','products_owner_id_index','products_category_id_index','products_supplier_id_index','products_stock_index','products_created_at_index']));
        Schema::table('sales',           fn($t) => $this->dropSafe($t, ['sales_tenant_id_index','sales_client_id_index','sales_user_id_index','sales_created_at_index','sales_status_index']));
        Schema::table('sale_items',      fn($t) => $this->dropSafe($t, ['sale_items_sale_id_index','sale_items_product_id_index','sale_items_tenant_id_index']));
        Schema::table('stock_movements', fn($t) => $this->dropSafe($t, ['sm_product_id_index','sm_tenant_id_index','sm_type_index','sm_created_at_index']));
        Schema::table('clients',         fn($t) => $this->dropSafe($t, ['clients_tenant_id_index','clients_created_at_index']));
        Schema::table('users',           fn($t) => $this->dropSafe($t, ['users_tenant_id_index','users_role_index']));
        Schema::table('tenants',         fn($t) => $this->dropSafe($t, ['tenants_is_active_index','tenants_subscription_ends_at_index']));
    }

    private function dropSafe(Blueprint $table, array $indexes): void
    {
        foreach ($indexes as $index) {
            try { $table->dropIndex($index); } catch (\Exception) {}
        }
    }
};
