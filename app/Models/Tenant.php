<?php
// app/Models/Tenant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'domain',
        'subdomain', // Ajouté
        'database_name',
        'db_username',
        'db_password',
        'email',
        'phone',
        'address',
        'logo',
        'is_active',
        'subscription_ends_at',
        'settings',
        
        // 👇 NOUVELLES COLONNES D'ABONNEMENT
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
        'subscription_metadata',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_ends_at' => 'datetime',
        'settings' => 'array',
        
        // 👇 NOUVEAUX CASTS
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
        'trial_ends_at' => 'date',
        'last_payment_date' => 'date',
        'subscription_metadata' => 'array',
        'has_trial' => 'boolean',
    ];

    /**
     * Tous les utilisateurs de ce tenant
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Le super_admin propriétaire de ce tenant
     */
    public function owner()
    {
        return $this->hasOne(User::class)->where('role', 'super_admin');
    }

    /**
     * 👇 TOUTES LES VENTES de ce tenant (via les utilisateurs)
     */
    public function sales()
    {
        return $this->hasManyThrough(
            Sale::class,
            User::class,
            'tenant_id', // Clé étrangère sur users
            'user_id',    // Clé étrangère sur sales
            'id',         // Clé locale sur tenants
            'id'          // Clé locale sur users
        );
    }

    /**
     * 👇 TOUS LES PRODUITS de ce tenant (via les utilisateurs)
     */
    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            User::class,
            'tenant_id', // Clé étrangère sur users
            'owner_id',   // Clé étrangère sur products (owner_id = user_id du propriétaire)
            'id',         // Clé locale sur tenants
            'id'          // Clé locale sur users
        );
    }

    /**
     * 👇 TOUS LES CLIENTS de ce tenant
     */
    public function clients()
    {
        return $this->hasManyThrough(
            Client::class,
            User::class,
            'tenant_id',
            'owner_id',
            'id',
            'id'
        );
    }

    /**
     * 👇 TOUS LES FOURNISSEURS de ce tenant
     */
    public function suppliers()
    {
        return $this->hasManyThrough(
            Supplier::class,
            User::class,
            'tenant_id',
            'owner_id',
            'id',
            'id'
        );
    }

    /**
     * 👇 TOUS LES MOUVEMENTS DE STOCK de ce tenant
     */
    public function stockMovements()
    {
        return $this->hasManyThrough(
            StockMovement::class,
            User::class,
            'tenant_id',
            'owner_id',
            'id',
            'id'
        );
    }

    /**
     * 👇 HISTORIQUE DES ABONNEMENTS
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * 👇 ABONNEMENT ACTIF
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
                    ->where('status', 'active')
                    ->where('end_date', '>', now())
                    ->latest();
    }

    // =====================================================
    // ✅ MÉTHODES DE GESTION DES ABONNEMENTS
    // =====================================================

    /**
     * Vérifier si l'abonnement est actif
     */
    public function hasActiveSubscription(): bool
    {
        // Si en période d'essai
        if ($this->payment_status === 'trial' && $this->trial_ends_at && $this->trial_ends_at->isFuture()) {
            return true;
        }
        
        // Si payé et pas expiré
        return $this->payment_status === 'paid' 
            && $this->subscription_end_date 
            && $this->subscription_end_date->isFuture();
    }

    /**
     * Vérifier si l'abonnement est expiré
     */
    public function isExpired(): bool
    {
        if ($this->payment_status === 'trial') {
            return $this->trial_ends_at && $this->trial_ends_at->isPast();
        }
        
        return $this->subscription_end_date && $this->subscription_end_date->isPast();
    }

    /**
     * Vérifier si le paiement est en retard
     */
    public function isOverdue(): bool
    {
        return $this->payment_status === 'overdue';
    }

    /**
     * Jours restants avant expiration
     */
    public function daysRemaining(): int
    {
        if ($this->payment_status === 'trial' && $this->trial_ends_at) {
            return max(0, Carbon::now()->diffInDays($this->trial_ends_at, false));
        }
        
        if ($this->payment_status === 'paid' && $this->subscription_end_date) {
            return max(0, Carbon::now()->diffInDays($this->subscription_end_date, false));
        }
        
        return 0;
    }

    /**
     * Prix formaté (en euros ou FCFA)
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->subscription_price / 100, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Libellé du cycle de facturation
     */
    public function getBillingCycleLabelAttribute(): string
    {
        return match($this->billing_cycle) {
            'monthly'   => 'Mensuel',
            'quarterly' => 'Trimestriel',
            'semester'  => 'Semestriel',
            'yearly'    => 'Annuel',
            default     => 'Mensuel'
        };
    }

    /**
     * Vérifier si le tenant est actif (ancienne méthode à conserver)
     */
    public function isActive(): bool
    {
        return $this->is_active && 
               (!$this->subscription_ends_at || $this->subscription_ends_at->isFuture());
    }

    /**
     * Nom d'affichage (priorité à company_name sinon name)
     */
    public function getDisplayNameAttribute()
    {
        return $this->company_name ?? $this->name ?? 'Quincaillerie';
    }

    /**
     * 👇 STATISTIQUES DU TENANT
     */
    public function getStatsAttribute()
    {
        return [
            'total_users' => $this->users()->count(),
            'total_sales' => $this->sales()->count(),
            'total_revenue' => $this->sales()->sum('total_price'),
            'total_products' => $this->products()->count(),
            'total_clients' => $this->clients()->count(),
            'total_suppliers' => $this->suppliers()->count(),
            'formatted_revenue' => number_format($this->sales()->sum('total_price'), 0, ',', ' ') . ' FCFA',
        ];
    }

    /**
     * 👇 DERNIÈRES VENTES (limité)
     */
    public function latestSales($limit = 5)
    {
        return $this->sales()
                    ->with(['client', 'user'])
                    ->latest()
                    ->limit($limit)
                    ->get();
    }

    /**
     * 👇 PRODUITS EN RUPTURE DE STOCK
     */
    public function outOfStockProducts()
    {
        return $this->products()->where('stock', 0)->get();
    }

    /**
     * 👇 PRODUITS EN STOCK FAIBLE
     */
    public function lowStockProducts($threshold = 5)
    {
        return $this->products()
                    ->where('stock', '>', 0)
                    ->where('stock', '<=', $threshold)
                    ->orderBy('stock')
                    ->get();
    }

    // =====================================================
    // 🔍 SCOPES POUR LES REQUÊTES
    // =====================================================

    /**
     * Scope pour les abonnements actifs
     */
    public function scopeActive($query)
    {
        return $query->where(function($q) {
            $q->where('payment_status', 'paid')
              ->where('subscription_end_date', '>', now())
              ->orWhere(function($q2) {
                  $q2->where('payment_status', 'trial')
                      ->where('trial_ends_at', '>', now());
              });
        });
    }

    /**
     * Scope pour les paiements en retard
     */
    public function scopeOverdue($query)
    {
        return $query->where('payment_status', 'overdue');
    }

    /**
     * Scope pour les essais qui expirent bientôt
     */
    public function scopeTrialExpiring($query, $days = 7)
    {
        return $query->where('payment_status', 'trial')
            ->where('trial_ends_at', '<=', now()->addDays($days))
            ->where('trial_ends_at', '>', now());
    }

    /**
     * Scope pour les abonnements qui expirent bientôt
     */
    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('payment_status', 'paid')
            ->where('subscription_end_date', '<=', now()->addDays($days))
            ->where('subscription_end_date', '>', now());
    }

    /**
     * Scope pour les abonnements expirés
     */
    public function scopeExpired($query)
    {
        return $query->where(function($q) {
            $q->where('payment_status', 'paid')
              ->where('subscription_end_date', '<=', now())
              ->orWhere(function($q2) {
                  $q2->where('payment_status', 'trial')
                      ->where('trial_ends_at', '<=', now());
              });
        });
    }
}