<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_owner_id_to_all_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected array $tables = [
        'products',
        'suppliers',
        'clients',
        'sales',
        'categories',
        'stock_movements',
        'sale_items',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && !Schema::hasColumn($table, 'owner_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->foreignId('owner_id')
                          ->nullable()
                          ->after('id')
                          ->constrained('users')
                          ->onDelete('cascade');
                    
                    $table->index('owner_id');
                });
            }
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'owner_id')) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropForeign(['owner_id']);
                    $table->dropIndex(['owner_id']);
                    $table->dropColumn('owner_id');
                });
            }
        }
    }
};