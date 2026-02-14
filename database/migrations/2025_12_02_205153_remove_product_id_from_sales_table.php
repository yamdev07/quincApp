<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // On vérifie d'abord que la colonne existe
        if (!Schema::hasColumn('sales', 'product_id')) {
            return;
        }

        Schema::table('sales', function (Blueprint $table) {
            // On tente de supprimer la FK si elle existe (sans casser)
            try {
                $table->dropForeign(['product_id']);
            } catch (\Throwable $e) {
                // ignore si la contrainte n'existe pas
            }

            // Puis on supprime la colonne
            try {
                $table->dropColumn('product_id');
            } catch (\Throwable $e) {
                // ignore si déjà supprimée
            }
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            if (!Schema::hasColumn('sales', 'product_id')) {
                $table->foreignId('product_id')
                    ->nullable()
                    ->constrained('products')
                    ->nullOnDelete();
            }
        });
    }
};
