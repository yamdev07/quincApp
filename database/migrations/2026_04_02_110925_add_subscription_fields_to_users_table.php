<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Colonnes d'abonnement
            if (!Schema::hasColumn('users', 'trial_ends_at')) {
                $table->timestamp('trial_ends_at')->nullable()->after('can_manage_users');
            }
            
            if (!Schema::hasColumn('users', 'subscription_ends_at')) {
                $table->timestamp('subscription_ends_at')->nullable()->after('trial_ends_at');
            }
            
            if (!Schema::hasColumn('users', 'subscription_status')) {
                $table->enum('subscription_status', ['trial', 'active', 'expired', 'cancelled'])
                      ->default('trial')
                      ->after('subscription_ends_at');
            }
            
            if (!Schema::hasColumn('users', 'fedapay_transaction_id')) {
                $table->string('fedapay_transaction_id')->nullable()->after('subscription_status');
            }
            
            if (!Schema::hasColumn('users', 'last_payment_at')) {
                $table->timestamp('last_payment_at')->nullable()->after('fedapay_transaction_id');
            }
            
            // Ajouter des indexes pour les performances
            $table->index('subscription_status');
            $table->index('subscription_ends_at');
            $table->index('trial_ends_at');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Supprimer les indexes d'abord
            $table->dropIndex(['subscription_status']);
            $table->dropIndex(['subscription_ends_at']);
            $table->dropIndex(['trial_ends_at']);
            
            // Supprimer les colonnes
            $table->dropColumn([
                'trial_ends_at',
                'subscription_ends_at',
                'subscription_status',
                'fedapay_transaction_id',
                'last_payment_at'
            ]);
        });
    }
};