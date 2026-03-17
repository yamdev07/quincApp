<?php
// database/migrations/[timestamp]_add_tenant_id_to_stock_movements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('owner_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        // Remplir tenant_id à partir des utilisateurs via les produits
        DB::statement('
            UPDATE stock_movements sm
            JOIN products p ON p.id = sm.product_id
            JOIN users u ON u.id = p.owner_id
            SET sm.tenant_id = u.tenant_id
        ');
    }

    public function down()
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};