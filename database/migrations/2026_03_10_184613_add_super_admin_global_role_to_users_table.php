<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_super_admin_global_role_to_users_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Modifier la colonne role pour accepter 'super_admin_global'
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
            'super_admin_global',
            'super_admin',
            'admin',
            'manager',
            'cashier',
            'storekeeper'
        ) DEFAULT 'cashier'");
    }

    public function down(): void
    {
        // Revenir à l'ancienne version
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM(
            'super_admin',
            'admin',
            'manager',
            'cashier',
            'storekeeper'
        ) DEFAULT 'cashier'");
    }
};