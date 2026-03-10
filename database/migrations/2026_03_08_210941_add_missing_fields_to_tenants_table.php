<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_missing_fields_to_tenants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Ajouter les champs manquants un par un
            if (!Schema::hasColumn('tenants', 'company_name')) {
                $table->string('company_name')->nullable()->after('name');
            }
            
            if (!Schema::hasColumn('tenants', 'email')) {
                $table->string('email')->nullable()->after('company_name');
            }
            
            if (!Schema::hasColumn('tenants', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            
            if (!Schema::hasColumn('tenants', 'address')) {
                $table->string('address')->nullable()->after('phone');
            }
            
            if (!Schema::hasColumn('tenants', 'logo')) {
                $table->string('logo')->nullable()->after('address');
            }
            
            if (!Schema::hasColumn('tenants', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('logo');
            }
            
            if (!Schema::hasColumn('tenants', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable()->after('is_active');
            }
            
            if (!Schema::hasColumn('tenants', 'settings')) {
                $table->json('settings')->nullable()->after('subscription_ends_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $columns = [
                'company_name',
                'email',
                'phone',
                'address',
                'logo',
                'is_active',
                'subscription_ends_at',
                'settings'
            ];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('tenants', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};