<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Check if old global unique index exists before dropping (IF EXISTS not supported pre-MySQL 8.0.3)
        $oldIndex = DB::select(
            "SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'clients' AND INDEX_NAME = 'clients_email_unique'"
        );
        if ($oldIndex[0]->cnt > 0) {
            DB::statement('ALTER TABLE `clients` DROP INDEX `clients_email_unique`');
        }

        // Add tenant-scoped composite unique (same email allowed across tenants)
        $newIndex = DB::select(
            "SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'clients' AND INDEX_NAME = 'clients_tenant_id_email_unique'"
        );
        if ($newIndex[0]->cnt === 0) {
            Schema::table('clients', function (Blueprint $table) {
                $table->unique(['tenant_id', 'email'], 'clients_tenant_id_email_unique');
            });
        }
    }

    public function down(): void
    {
        $tenantIndex = DB::select(
            "SELECT COUNT(*) as cnt FROM information_schema.STATISTICS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'clients' AND INDEX_NAME = 'clients_tenant_id_email_unique'"
        );
        if ($tenantIndex[0]->cnt > 0) {
            DB::statement('ALTER TABLE `clients` DROP INDEX `clients_tenant_id_email_unique`');
        }

        Schema::table('clients', function (Blueprint $table) {
            $table->unique('email', 'clients_email_unique');
        });
    }
};
