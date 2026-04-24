<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the global email unique index if it exists (raw SQL supports IF EXISTS)
        DB::statement('ALTER TABLE `clients` DROP INDEX IF EXISTS `clients_email_unique`');

        // Add tenant-scoped composite unique (same email allowed across tenants)
        $exists = DB::select(
            "SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'clients' AND INDEX_NAME = 'clients_tenant_id_email_unique'"
        );

        if ($exists[0]->cnt === 0) {
            Schema::table('clients', function (Blueprint $table) {
                $table->unique(['tenant_id', 'email'], 'clients_tenant_id_email_unique');
            });
        }
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE `clients` DROP INDEX IF EXISTS `clients_tenant_id_email_unique`');

        Schema::table('clients', function (Blueprint $table) {
            $table->unique('email', 'clients_email_unique');
        });
    }
};
