<?php
// app/Traits/HasSubscription.php

namespace App\Traits;

use Carbon\Carbon;

trait HasSubscription
{
    /**
     * Vérifie si l'utilisateur est en période d'essai
     */
    public function isOnTrial()
    {
        return $this->subscription_status === 'trial' && 
               $this->trial_ends_at && 
               $this->trial_ends_at->isFuture();
    }
    
    /**
     * Vérifie si l'abonnement est actif
     */
    public function isSubscribed()
    {
        return $this->subscription_status === 'active' && 
               $this->subscription_ends_at && 
               $this->subscription_ends_at->isFuture();
    }
    
    /**
     * Vérifie si l'utilisateur a accès à l'application
     */
    public function hasAccess()
    {
        // Le super admin global a toujours accès
        if (isset($this->is_super_admin_global) && $this->is_super_admin_global) {
            return true;
        }
        
        return $this->isOnTrial() || $this->isSubscribed();
    }
    
    /**
     * Jours restants d'essai
     */
    public function trialDaysRemaining()
    {
        if (!$this->trial_ends_at) {
            return 0;
        }
        
        $days = now()->diffInDays($this->trial_ends_at, false);
        return $days > 0 ? $days : 0;
    }
    
    /**
     * Jours restants d'abonnement
     */
    public function subscriptionDaysRemaining()
    {
        if (!$this->subscription_ends_at) {
            return 0;
        }
        
        $days = now()->diffInDays($this->subscription_ends_at, false);
        return $days > 0 ? $days : 0;
    }
    
    /**
     * Activer l'abonnement après paiement
     */
    public function activateSubscription($transactionId, $months = 1)
    {
        $this->update([
            'subscription_status' => 'active',
            'subscription_ends_at' => now()->addMonths($months),
            'fedapay_transaction_id' => $transactionId,
            'last_payment_at' => now(),
        ]);
        
        return $this;
    }
    
    /**
     * Démarrer la période d'essai
     */
    public function startTrial($days = 14)
    {
        $this->update([
            'subscription_status' => 'trial',
            'trial_ends_at' => now()->addDays($days),
            'subscription_ends_at' => null,
            'fedapay_transaction_id' => null,
        ]);
        
        return $this;
    }
    
    /**
     * Expirer l'abonnement
     */
    public function expireSubscription()
    {
        $this->update([
            'subscription_status' => 'expired',
        ]);
        
        return $this;
    }
    
    /**
     * Vérifier si l'abonnement expire bientôt (dans 3 jours)
     */
    public function subscriptionExpiresSoon()
    {
        if (!$this->subscription_ends_at) {
            return false;
        }
        
        return now()->diffInDays($this->subscription_ends_at, false) <= 3 && 
               $this->subscription_ends_at->isFuture();
    }
}