<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            if (!Schema::hasColumn('sale_items', 'tenant_id')) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained('tenants')->onDelete('cascade');
            }
            if (!Schema::hasColumn('sale_items', 'owner_id')) {
                $table->foreignId('owner_id')->nullable()->after('tenant_id')->constrained('users')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sale_items', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
            $table->dropForeign(['owner_id']);
            $table->dropColumn('owner_id');
        });
    }
};