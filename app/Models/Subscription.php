<?php

namespace App\Models;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Subscription extends Model
{
    use TenantScope;

    protected $fillable = [
        'tenant_id',
        'plan_type',
        'amount',
        'start_date',
        'end_date',
        'status',
        'payment_method',
        'transaction_id',
        'metadata',
        // 'owner_id',  // SUPPRIMÉ - cette colonne n'existe pas dans la base
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'amount' => 'decimal:2',
        'metadata' => 'array',
    ];

    // ============ RELATIONS ============

    /**
     * Tenant (quincaillerie) associé à l'abonnement
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Propriétaire (super_admin) qui a créé l'abonnement
     * NOTE: Cette relation est commentée car la colonne owner_id n'existe pas
     * Si vous voulez l'utiliser, il faut d'abord ajouter la colonne owner_id dans la table subscriptions
     */
    // public function owner(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'owner_id');
    // }

    // ============ ACCESSORS ============

    /**
     * Vérifie si l'abonnement est actif
     */
    public function getIsActiveAttribute(): bool
    {
        return $this->status === 'active' && 
               $this->end_date && 
               $this->end_date->isFuture();
    }

    /**
     * Vérifie si l'abonnement est expiré
     */
    public function getIsExpiredAttribute(): bool
    {
        return $this->end_date && $this->end_date->isPast();
    }

    /**
     * Vérifie si l'abonnement est en période d'essai
     */
    public function getIsTrialAttribute(): bool
    {
        return $this->status === 'trial';
    }

    /**
     * Vérifie si l'abonnement est en attente
     */
    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Vérifie si l'abonnement est annulé
     */
    public function getIsCancelledAttribute(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Nombre de jours restants
     */
    public function getDaysRemainingAttribute(): ?int
    {
        if (!$this->end_date) return null;
        
        if ($this->end_date->isPast()) return 0;
        
        return now()->diffInDays($this->end_date, false);
    }

    /**
     * Nombre de jours écoulés depuis le début
     */
    public function getDaysElapsedAttribute(): int
    {
        if (!$this->start_date) return 0;
        
        return $this->start_date->diffInDays(now());
    }

    /**
     * Pourcentage de l'abonnement consommé
     */
    public function getConsumptionPercentageAttribute(): float
    {
        if (!$this->start_date || !$this->end_date) return 0;
        
        $totalDays = $this->start_date->diffInDays($this->end_date);
        $elapsedDays = $this->start_date->diffInDays(now());
        
        if ($totalDays <= 0) return 0;
        
        return min(100, ($elapsedDays / $totalDays) * 100);
    }

    /**
     * Statut en français
     */
    public function getStatusLabelAttribute(): string
    {
        $statuses = [
            'active' => 'Actif',
            'pending' => 'En attente',
            'trial' => 'Essai',
            'cancelled' => 'Annulé',
            'expired' => 'Expiré',
        ];
        
        if ($this->is_expired && $this->status === 'active') {
            return 'Expiré';
        }
        
        return $statuses[$this->status] ?? 'Inconnu';
    }

    /**
     * Classe CSS pour le statut
     */
    public function getStatusClassAttribute(): string
    {
        if ($this->is_expired || $this->status === 'expired') {
            return 'danger';
        }
        
        $classes = [
            'active' => 'success',
            'pending' => 'warning',
            'trial' => 'info',
            'cancelled' => 'secondary',
        ];
        
        return $classes[$this->status] ?? 'secondary';
    }

    /**
     * Plan type en français
     */
    public function getPlanLabelAttribute(): string
    {
        $plans = [
            'monthly' => 'Mensuel',
            'quarterly' => 'Trimestriel',
            'semester' => 'Semestriel',
            'yearly' => 'Annuel',
        ];
        
        return $plans[$this->plan_type] ?? ucfirst($this->plan_type);
    }

    /**
     * Montant formaté
     */
    public function getFormattedAmountAttribute(): string
    {
        return number_format($this->amount, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Date de début formatée
     */
    public function getFormattedStartDateAttribute(): string
    {
        return $this->start_date ? $this->start_date->format('d/m/Y') : 'N/A';
    }

    /**
     * Date de fin formatée
     */
    public function getFormattedEndDateAttribute(): string
    {
        return $this->end_date ? $this->end_date->format('d/m/Y') : 'N/A';
    }

    /**
     * Période formatée
     */
    public function getFormattedPeriodAttribute(): string
    {
        if (!$this->start_date || !$this->end_date) {
            return 'N/A';
        }
        
        return $this->formatted_start_date . ' → ' . $this->formatted_end_date;
    }

    /**
     * Durée totale en jours
     */
    public function getTotalDaysAttribute(): int
    {
        if (!$this->start_date || !$this->end_date) return 0;
        
        return $this->start_date->diffInDays($this->end_date);
    }

    // ============ SCOPES ============

    /**
     * Abonnements actifs
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                     ->where('end_date', '>', now());
    }

    /**
     * Abonnements expirés
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'active')
                     ->where('end_date', '<=', now());
    }

    /**
     * Abonnements qui expirent bientôt
     */
    public function scopeExpiringSoon($query, $days = 7)
    {
        return $query->where('status', 'active')
                     ->where('end_date', '>', now())
                     ->where('end_date', '<=', now()->addDays($days));
    }

    /**
     * Abonnements en essai
     */
    public function scopeTrial($query)
    {
        return $query->where('status', 'trial');
    }

    /**
     * Abonnements en attente
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Abonnements annulés
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Abonnements par type de plan
     */
    public function scopeByPlan($query, $planType)
    {
        return $query->where('plan_type', $planType);
    }

    /**
     * Abonnements entre deux dates
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('start_date', [$startDate, $endDate])
                     ->orWhereBetween('end_date', [$startDate, $endDate]);
    }

    // ============ MÉTHODES ============

    /**
     * Vérifie si l'abonnement est actif
     * (méthode alias pour accessor)
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Prolonger l'abonnement
     */
    public function extend($months = 1): self
    {
        $this->end_date = $this->end_date ? $this->end_date->addMonths($months) : now()->addMonths($months);
        $this->status = 'active';
        $this->save();
        
        return $this;
    }

    /**
     * Renouveler l'abonnement - Version SANS owner_id
     */
    public function renew($amount = null, $paymentMethod = null, $transactionId = null): self
    {
        $startDate = $this->end_date && $this->end_date->isFuture() 
            ? $this->end_date->copy()->addDay() 
            : now();
        
        $endDate = $startDate->copy()->addMonth();
        
        if ($this->plan_type === 'quarterly') {
            $endDate = $startDate->copy()->addMonths(3);
        } elseif ($this->plan_type === 'semester') {
            $endDate = $startDate->copy()->addMonths(6);
        } elseif ($this->plan_type === 'yearly') {
            $endDate = $startDate->copy()->addYear();
        }
        
        $this->start_date = $startDate;
        $this->end_date = $endDate;
        
        if ($amount !== null) {
            $this->amount = $amount;
        }
        
        if ($paymentMethod !== null) {
            $this->payment_method = $paymentMethod;
        }
        
        if ($transactionId !== null) {
            $this->transaction_id = $transactionId;
        }
        
        $this->status = 'active';
        $this->save();
        
        return $this;
    }

    /**
     * Annuler l'abonnement
     */
    public function cancel(): self
    {
        $this->status = 'cancelled';
        $this->save();
        
        return $this;
    }

    /**
     * Activer l'abonnement
     */
    public function activate(): self
    {
        $this->status = 'active';
        $this->save();
        
        return $this;
    }

    /**
     * Mettre en attente l'abonnement
     */
    public function pending(): self
    {
        $this->status = 'pending';
        $this->save();
        
        return $this;
    }

    /**
     * Calculer le montant du renouvellement
     */
    public function calculateRenewalAmount(): float
    {
        return $this->amount;
    }

    /**
     * Récupérer les statistiques de l'abonnement
     */
    public function getStatistics(): array
    {
        return [
            'id' => $this->id,
            'tenant_id' => $this->tenant_id,
            'plan' => $this->plan_label,
            'status' => $this->status_label,
            'status_class' => $this->status_class,
            'amount' => $this->formatted_amount,
            'period' => $this->formatted_period,
            'days_remaining' => $this->days_remaining,
            'days_elapsed' => $this->days_elapsed,
            'consumption_percentage' => round($this->consumption_percentage, 2),
            'total_days' => $this->total_days,
            'is_active' => $this->is_active,
            'is_expired' => $this->is_expired,
            'payment_method' => $this->payment_method,
            'transaction_id' => $this->transaction_id,
        ];
    }

    /**
     * Récupérer les statistiques globales des abonnements
     */
    public static function getGlobalStats($tenantId = null): array
    {
        $query = self::query();
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        $total = (clone $query)->count();
        $active = (clone $query)->active()->count();
        $expired = (clone $query)->expired()->count();
        $trial = (clone $query)->trial()->count();
        $pending = (clone $query)->pending()->count();
        $cancelled = (clone $query)->cancelled()->count();
        $expiringSoon = (clone $query)->expiringSoon(7)->count();
        
        $totalRevenue = (clone $query)->where('status', 'active')
            ->sum('amount');
        
        $averageAmount = (clone $query)->where('status', 'active')
            ->avg('amount');
        
        $statsByPlan = (clone $query)
            ->select(
                'plan_type',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(amount) as total_revenue'),
                DB::raw('AVG(amount) as average_amount')
            )
            ->groupBy('plan_type')
            ->get()
            ->mapWithKeys(function($item) {
                $plans = [
                    'monthly' => 'Mensuel',
                    'quarterly' => 'Trimestriel',
                    'semester' => 'Semestriel',
                    'yearly' => 'Annuel',
                ];
                
                $key = $plans[$item->plan_type] ?? $item->plan_type;
                
                return [$key => [
                    'total' => $item->total,
                    'total_revenue' => $item->total_revenue,
                    'average_amount' => $item->average_amount,
                ]];
            });
        
        return [
            'total_subscriptions' => $total,
            'active_subscriptions' => $active,
            'expired_subscriptions' => $expired,
            'trial_subscriptions' => $trial,
            'pending_subscriptions' => $pending,
            'cancelled_subscriptions' => $cancelled,
            'expiring_soon' => $expiringSoon,
            'total_revenue' => $totalRevenue,
            'average_amount' => round($averageAmount, 2),
            'stats_by_plan' => $statsByPlan,
        ];
    }

    /**
     * Vérifier si un tenant a un abonnement actif
     */
    public static function hasActiveSubscription($tenantId): bool
    {
        return self::where('tenant_id', $tenantId)
            ->active()
            ->exists();
    }

    /**
     * Récupérer l'abonnement actif d'un tenant
     */
    public static function getActiveForTenant($tenantId): ?self
    {
        return self::where('tenant_id', $tenantId)
            ->active()
            ->latest()
            ->first();
    }

    /**
     * Créer un abonnement pour un tenant - Version SANS owner_id
     */
    public static function createForTenant(
        $tenantId, 
        $planType, 
        $amount, 
        $startDate = null, 
        $endDate = null, 
        $status = 'pending',
        $paymentMethod = null,
        $transactionId = null,
        $metadata = []
    ): self {
        $startDate = $startDate ?? now();
        
        if (!$endDate) {
            switch ($planType) {
                case 'monthly':
                    $endDate = $startDate->copy()->addMonth();
                    break;
                case 'quarterly':
                    $endDate = $startDate->copy()->addMonths(3);
                    break;
                case 'semester':
                    $endDate = $startDate->copy()->addMonths(6);
                    break;
                case 'yearly':
                    $endDate = $startDate->copy()->addYear();
                    break;
                default:
                    $endDate = $startDate->copy()->addMonth();
            }
        }
        
        return self::create([
            'tenant_id' => $tenantId,
            'plan_type' => $planType,
            'amount' => $amount,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => $status,
            'payment_method' => $paymentMethod,
            'transaction_id' => $transactionId,
            'metadata' => $metadata,
            // 'owner_id' => auth()->id(),  // SUPPRIMÉ
        ]);
    }
}