<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('ifu')->nullable()->after('address');
            $table->string('rccm')->nullable()->after('ifu');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('rccm');
        });
    }

    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['ifu', 'rccm', 'tax_rate']);
        });
    }
};
