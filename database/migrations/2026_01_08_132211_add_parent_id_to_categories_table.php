<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1) parent_id (safe)
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'parent_id')) {
                $table->foreignId('parent_id')
                    ->nullable()
                    ->constrained('categories')
                    ->nullOnDelete();
            }
        });

        // 2) supprimer sub_name (safe)
        if (Schema::hasColumn('categories', 'sub_name')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->dropColumn('sub_name');
            });
        }

        // 3) ajouter color + icon (safe, sans after)
        Schema::table('categories', function (Blueprint $table) {
            if (!Schema::hasColumn('categories', 'color')) {
                $table->string('color')->nullable();
            }

            if (!Schema::hasColumn('categories', 'icon')) {
                $table->string('icon')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            // drop FK parent_id si existe
            try {
                $table->dropForeign(['parent_id']);
            } catch (\Throwable $e) {}

            // drop colonnes si existent
            $columns = [];
            foreach (['parent_id', 'color', 'icon'] as $col) {
                if (Schema::hasColumn('categories', $col)) {
                    $columns[] = $col;
                }
            }

            if (!empty($columns)) {
                $table->dropColumn($columns);
            }
        });
    }
};
