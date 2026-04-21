<?php
// app/Models/Tenant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'domain',
        'subdomain',
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
        
        // Colonnes d'abonnement
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
        'owner_id',
        'ifu',
        'rccm',
        'tax_rate',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'tax_rate' => 'decimal:2',
        'subscription_ends_at' => 'datetime',
        'settings' => 'array',
        
        // Casts pour l'abonnement
        'subscription_start_date' => 'datetime',
        'subscription_end_date' => 'datetime',
        'trial_ends_at' => 'datetime',
        'last_payment_date' => 'datetime',
        'subscription_metadata' => 'array',
        'has_trial' => 'boolean',
        'subscription_price' => 'decimal:2',
        'last_payment_amount' => 'decimal:2',
    ];

    // ============ RELATIONS ============

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
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Toutes les ventes de ce tenant (via les utilisateurs)
     */
    public function sales()
    {
        return $this->hasManyThrough(
            Sale::class,
            User::class,
            'tenant_id',
            'user_id',
            'id',
            'id'
        );
    }

    /**
     * Tous les produits de ce tenant
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Tous les clients de ce tenant
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * Tous les fournisseurs de ce tenant
     */
    public function suppliers()
    {
        return $this->hasMany(Supplier::class);
    }

    /**
     * Tous les mouvements de stock de ce tenant
     */
    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Historique des abonnements
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    /**
     * Abonnement actif
     */
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class)
                    ->where('status', 'active')
                    ->where('end_date', '>', now())
                    ->latest();
    }

    // ============ MÉTHODES DE GESTION DES ABONNEMENTS ============

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
     * Prix formaté
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->subscription_price, 0, ',', ' ') . ' FCFA';
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
     * Vérifier si le tenant est actif (ancienne méthode)
     */
    public function isActive(): bool
    {
        return $this->is_active && 
               (!$this->subscription_ends_at || $this->subscription_ends_at->isFuture());
    }

    /**
     * Nom d'affichage
     */
    public function getDisplayNameAttribute()
    {
        return $this->company_name ?? $this->name ?? 'Quincaillerie';
    }

    /**
     * Statistiques du tenant - Version optimisée
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
     * Dernières ventes (limité)
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
     * Produits en rupture de stock
     */
    public function outOfStockProducts()
    {
        return $this->products()->where('stock', 0)->get();
    }

    /**
     * Produits en stock faible
     */
    public function lowStockProducts($threshold = 5)
    {
        return $this->products()
                    ->where('stock', '>', 0)
                    ->where('stock', '<=', $threshold)
                    ->orderBy('stock')
                    ->get();
    }

    // ============ SCOPES ============

    /**
     * Scope pour les abonnements actifs - Version compatible PostgreSQL
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
     * Scope pour les abonnements expirés - Version compatible PostgreSQL
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

    // ============ MÉTHODES STATISTIQUES AVANCÉES ============

    /**
     * Récupérer les statistiques globales des tenants
     * Version optimisée pour PostgreSQL
     */
    public static function getGlobalTenantStats(): array
    {
        $total = self::count();
        $active = self::active()->count();
        $expired = self::expired()->count();
        $overdue = self::overdue()->count();
        $trial = self::where('payment_status', 'trial')->count();
        
        $totalRevenue = self::where('payment_status', 'paid')
            ->sum('subscription_price');
        
        $averageRevenue = self::where('payment_status', 'paid')
            ->avg('subscription_price');
        
        // Statistiques par cycle de facturation
        $statsByCycle = self::select(
                'billing_cycle',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(subscription_price) as total_revenue'),
                DB::raw('AVG(subscription_price) as average_amount')
            )
            ->where('payment_status', 'paid')
            ->groupBy('billing_cycle')
            ->get()
            ->mapWithKeys(function($item) {
                $cycles = [
                    'monthly' => 'Mensuel',
                    'quarterly' => 'Trimestriel',
                    'semester' => 'Semestriel',
                    'yearly' => 'Annuel',
                ];
                
                $key = $cycles[$item->billing_cycle] ?? $item->billing_cycle;
                
                return [$key => [
                    'total' => $item->total,
                    'total_revenue' => $item->total_revenue,
                    'average_amount' => round($item->average_amount, 2),
                ]];
            });
        
        return [
            'total_tenants' => $total,
            'active_tenants' => $active,
            'expired_tenants' => $expired,
            'overdue_tenants' => $overdue,
            'trial_tenants' => $trial,
            'total_revenue' => $totalRevenue,
            'formatted_revenue' => number_format($totalRevenue, 0, ',', ' ') . ' FCFA',
            'average_revenue' => round($averageRevenue, 2),
            'formatted_average_revenue' => number_format($averageRevenue, 0, ',', ' ') . ' FCFA',
            'stats_by_cycle' => $statsByCycle,
        ];
    }

    /**
     * Récupérer les tenants avec le plus de revenus
     */
    public static function getTopRevenueTenants($limit = 10)
    {
        return self::where('payment_status', 'paid')
            ->orderBy('subscription_price', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($tenant) {
                return [
                    'id' => $tenant->id,
                    'name' => $tenant->display_name,
                    'subscription_price' => $tenant->formatted_price,
                    'billing_cycle' => $tenant->billing_cycle_label,
                    'status' => $tenant->payment_status,
                ];
            });
    }

    /**
     * Vérifier la cohérence des données
     */
    public function checkConsistency(): array
    {
        $issues = [];
        
        // Vérifier que le propriétaire existe
        if ($this->owner_id && !$this->owner) {
            $issues[] = 'Le propriétaire associé n\'existe pas';
        }
        
        // Vérifier les dates d'abonnement
        if ($this->payment_status === 'paid' && !$this->subscription_end_date) {
            $issues[] = 'Abonnement payé sans date de fin';
        }
        
        if ($this->payment_status === 'trial' && !$this->trial_ends_at) {
            $issues[] = 'Période d\'essai sans date de fin';
        }
        
        // Vérifier la cohérence des dates
        if ($this->subscription_start_date && $this->subscription_end_date) {
            if ($this->subscription_start_date->isAfter($this->subscription_end_date)) {
                $issues[] = 'La date de début est postérieure à la date de fin';
            }
        }
        
        return [
            'is_consistent' => empty($issues),
            'issues' => $issues,
        ];
    }
}