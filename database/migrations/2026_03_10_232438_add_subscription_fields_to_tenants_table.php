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
        Schema::table('tenants', function (Blueprint $table) {
            // Prix de l'abonnement (en centimes pour éviter les erreurs d'arrondi)
            // 29900 = 299,00 € (ou FCFA * 100)
            $table->integer('subscription_price')->default(29900)->after('is_active');
            
            // Cycle de facturation
            $table->enum('billing_cycle', [
                'monthly',      // Mensuel
                'quarterly',    // Trimestriel (3 mois)
                'semester',     // Semestriel (6 mois)
                'yearly'        // Annuel
            ])->default('monthly')->after('subscription_price');
            
            // Dates d'abonnement
            $table->date('subscription_start_date')->nullable()->after('billing_cycle');
            $table->date('subscription_end_date')->nullable()->after('subscription_start_date');
            
            // Période d'essai
            $table->boolean('has_trial')->default(true)->after('subscription_end_date');
            $table->integer('trial_days')->default(14)->after('has_trial');
            $table->date('trial_ends_at')->nullable()->after('trial_days');
            
            // Statut de paiement
            $table->enum('payment_status', [
                'paid',         // Payé
                'pending',      // En attente
                'overdue',      // En retard
                'trial'         // Période d'essai
            ])->default('trial')->after('trial_ends_at');
            
            // Dernier paiement
            $table->date('last_payment_date')->nullable()->after('payment_status');
            $table->decimal('last_payment_amount', 10, 2)->nullable()->after('last_payment_date');
            
            // Pour Stripe/PayPal plus tard
            $table->string('stripe_customer_id')->nullable()->after('last_payment_amount');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_customer_id');
            $table->string('paypal_subscription_id')->nullable()->after('stripe_subscription_id');
            
            // Métadonnées
            $table->json('subscription_metadata')->nullable()->after('paypal_subscription_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_price',
                'billing_cycle',
                'subscription_start_date',
                'subscription_end_date',
                'has_trial',
                'trial_days',
                'trial_ends_at',
                'payment_status',
                'last_payment_date',
                'last_payment_amount',
                'stripe_customer_id',
                'stripe_subscription_id',
                'paypal_subscription_id',
                'subscription_metadata'
            ]);
        });
    }
};