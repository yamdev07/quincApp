<?php
// database/migrations/[timestamp]_add_tenant_id_to_products_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('owner_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        // Remplir tenant_id à partir des utilisateurs
        DB::statement('
            UPDATE products p
            JOIN users u ON u.id = p.owner_id
            SET p.tenant_id = u.tenant_id
        ');
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};