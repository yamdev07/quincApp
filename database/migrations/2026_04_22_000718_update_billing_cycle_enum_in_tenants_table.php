<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Ajouter 'starter' à l'ENUM billing_cycle
        DB::statement("ALTER TABLE tenants MODIFY billing_cycle ENUM('starter','monthly','quarterly','semester','yearly') NOT NULL DEFAULT 'monthly'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tenants MODIFY billing_cycle ENUM('monthly','quarterly','semester','yearly') NOT NULL DEFAULT 'monthly'");
    }
};
