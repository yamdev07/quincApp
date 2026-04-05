<?php
// database/migrations/xxxx_xx_xx_xxxxxx_add_owner_id_to_tenants_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (!Schema::hasColumn('tenants', 'owner_id')) {
                $table->foreignId('owner_id')->nullable()->after('id')->constrained('users');
            }
        });
    }

    public function down()
    {
        Schema::table('tenants', function (Blueprint $table) {
            if (Schema::hasColumn('tenants', 'owner_id')) {
                $table->dropForeign(['owner_id']);
                $table->dropColumn('owner_id');
            }
        });
    }
};