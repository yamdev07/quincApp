<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Supprimer le champ email
            $table->dropColumn('email');
            
            // Ajouter le champ phone (uniquement chiffres)
            $table->string('phone', 20)->nullable()->after('contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            // Restaurer email
            $table->string('email')->nullable()->after('contact');
            
            // Supprimer phone
            $table->dropColumn('phone');
        });
    }
};