<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->enum('plan', ['starter', 'business', 'pro'])->default('business')->after('subscription_price');
        });

        // Dériver le plan depuis subscription_price existant
        DB::statement("UPDATE tenants SET plan = CASE
            WHEN subscription_price <= 10000 THEN 'starter'
            WHEN subscription_price <= 15000 THEN 'business'
            ELSE 'pro'
        END");
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('plan');
        });
    }
};
