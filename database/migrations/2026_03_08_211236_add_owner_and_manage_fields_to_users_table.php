<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_owner_and_manage_fields_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Ajouter owner_id (lien vers le super_admin propriétaire)
            if (!Schema::hasColumn('users', 'owner_id')) {
                $table->foreignId('owner_id')
                      ->nullable()
                      ->after('role')
                      ->constrained('users')
                      ->onDelete('cascade');
            }
            
            // Ajouter can_manage_users (permission pour les admins délégués)
            if (!Schema::hasColumn('users', 'can_manage_users')) {
                $table->boolean('can_manage_users')
                      ->default(false)
                      ->after('owner_id');
            }
            
            // Ajouter des index pour les performances
            if (Schema::hasColumn('users', 'owner_id')) {
                $table->index('owner_id', 'users_owner_id_index');
            }
            
            if (Schema::hasColumn('users', 'tenant_id')) {
                $table->index('tenant_id', 'users_tenant_id_index');
            }
            
            if (Schema::hasColumn('users', 'role')) {
                $table->index('role', 'users_role_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $columns = ['owner_id', 'can_manage_users'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    // Supprimer la clé étrangère pour owner_id
                    if ($column === 'owner_id') {
                        $table->dropForeign(['owner_id']);
                    }
                    
                    // Supprimer l'index
                    $table->dropIndex('users_' . $column . '_index');
                    
                    // Supprimer la colonne
                    $table->dropColumn($column);
                }
            }
            
            // Supprimer les index des colonnes existantes
            if (Schema::hasColumn('users', 'tenant_id')) {
                $table->dropIndex('users_tenant_id_index');
            }
            
            if (Schema::hasColumn('users', 'role')) {
                $table->dropIndex('users_role_index');
            }
        });
    }
};