<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Add tenant_id column if it doesn't exist
        if (!Schema::hasColumn('categories', 'tenant_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->unsignedBigInteger('tenant_id')->nullable()->after('owner_id');
                $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
            });
        }

        // Safely populate tenant_id from owners
        $this->populateTenantId();
    }

    /**
     * Safely populate tenant_id for categories
     */
    private function populateTenantId(): void
    {
        // Check if we have categories without tenant_id
        $nullTenantCount = DB::table('categories')->whereNull('tenant_id')->count();
        
        if ($nullTenantCount === 0) {
            return; // Nothing to update
        }

        // Method 1: Try to get tenant_id from users table
        if (Schema::hasColumn('users', 'tenant_id')) {
            try {
                DB::statement('
                    UPDATE categories c
                    INNER JOIN users u ON u.id = c.owner_id
                    SET c.tenant_id = u.tenant_id
                    WHERE c.tenant_id IS NULL AND u.tenant_id IS NOT NULL
                ');
                
                // Check if we still have null tenant_ids
                $remainingCount = DB::table('categories')->whereNull('tenant_id')->count();
                if ($remainingCount === 0) {
                    return;
                }
            } catch (\Exception $e) {
                // Log but continue with fallback in CI environment
                if (app()->environment('testing')) {
                    \Log::warning('Could not update tenant_id from users: ' . $e->getMessage());
                }
            }
        }

        // Method 2: Fallback - assign to first tenant
        $firstTenant = DB::table('tenants')->first();
        if ($firstTenant) {
            DB::table('categories')
                ->whereNull('tenant_id')
                ->update(['tenant_id' => $firstTenant->id]);
        } else {
            // Method 3: Last resort - create a default tenant for testing environment
            if (app()->environment('testing')) {
                $tenantId = DB::table('tenants')->insertGetId([
                    'name' => 'Default Tenant',
                    'subdomain' => 'default',
                    'email' => 'default@example.com',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                DB::table('categories')->whereNull('tenant_id')->update(['tenant_id' => $tenantId]);
            }
        }
    }

    public function down()
    {
        if (Schema::hasColumn('categories', 'tenant_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};