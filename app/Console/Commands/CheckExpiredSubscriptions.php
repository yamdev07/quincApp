<?php
// app/Console/Commands/CheckExpiredSubscriptions.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Illuminate\Support\Facades\Log;

class CheckExpiredSubscriptions extends Command
{
    protected $signature = 'subscriptions:check';
    protected $description = 'Vérifie les abonnements expirés et désactive les comptes';

    public function handle()
    {
        $this->info('🔍 Vérification des abonnements...');

        // 1. Désactiver les essais expirés
        $expiredTrials = Tenant::where('payment_status', 'trial')
            ->where('trial_ends_at', '<', now())
            ->get();

        foreach ($expiredTrials as $tenant) {
            $tenant->update(['is_active' => false]);
            $this->warn("❌ Essai expiré : {$tenant->company_name}");
            Log::info("Essai expiré - Tenant {$tenant->id}");
        }

        // 2. Désactiver les abonnements payés expirés
        $expiredSubscriptions = Tenant::where('payment_status', 'paid')
            ->where('subscription_end_date', '<', now())
            ->get();

        foreach ($expiredSubscriptions as $tenant) {
            $tenant->update([
                'payment_status' => 'expired',
                'is_active' => false
            ]);
            $this->warn("❌ Abonnement expiré : {$tenant->company_name}");
            Log::info("Abonnement expiré - Tenant {$tenant->id}");
        }

        $this->info('✅ Vérification terminée.');
        
        return Command::SUCCESS;
    }
}