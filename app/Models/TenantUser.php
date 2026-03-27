<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Modèle User spécifique aux bases de données tenant
 * 
 * Ce modèle représente les utilisateurs d'une quincaillerie (tenant)
 * Il étend TenantModel qui gère automatiquement la connexion à la base de données du tenant
 */
class TenantUser extends TenantModel
{
    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
        'can_manage_users',
        'can_manage_stock',
        'can_manage_sales',
        'can_manage_clients',
        'can_view_reports',
        'last_login_at',
        'last_login_ip',
        'email_verified_at',
        'remember_token',
        'owner_id',
        'tenant_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'can_manage_users' => 'boolean',
        'can_manage_stock' => 'boolean',
        'can_manage_sales' => 'boolean',
        'can_manage_clients' => 'boolean',
        'can_view_reports' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============ RELATIONS ============

    /**
     * Ventes effectuées par cet utilisateur
     */
    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class, 'user_id');
    }

    /**
     * Mouvements de stock effectués par cet utilisateur
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class, 'user_id');
    }

    /**
     * Produits créés par cet utilisateur
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'user_id');
    }

    /**
     * Clients créés par cet utilisateur
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class, 'user_id');
    }

    /**
     * Fournisseurs créés par cet utilisateur
     */
    public function suppliers(): HasMany
    {
        return $this->hasMany(Supplier::class, 'user_id');
    }

    /**
     * Le propriétaire (super_admin) de ce tenant
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(TenantUser::class, 'owner_id');
    }

    /**
     * Utilisateurs créés par cet utilisateur (si admin)
     */
    public function createdUsers(): HasMany
    {
        return $this->hasMany(TenantUser::class, 'owner_id');
    }

    // ============ ACCESSORS ============

    /**
     * Rôle formaté en français
     */
    public function getRoleLabelAttribute(): string
    {
        $roles = [
            'super_admin' => 'Super Administrateur',
            'admin' => 'Administrateur',
            'manager' => 'Gestionnaire',
            'cashier' => 'Caissier',
            'storekeeper' => 'Magasinier',
        ];

        return $roles[$this->role] ?? ucfirst($this->role);
    }

    /**
     * Classe CSS pour le rôle
     */
    public function getRoleClassAttribute(): string
    {
        $classes = [
            'super_admin' => 'bg-purple-100 text-purple-800',
            'admin' => 'bg-red-100 text-red-800',
            'manager' => 'bg-blue-100 text-blue-800',
            'cashier' => 'bg-green-100 text-green-800',
            'storekeeper' => 'bg-yellow-100 text-yellow-800',
        ];

        return $classes[$this->role] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Initiales de l'utilisateur
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        $initials = '';

        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }

        return substr($initials, 0, 2);
    }

    /**
     * Statut de l'utilisateur (actif/inactif)
     */
    public function getStatusLabelAttribute(): string
    {
        return $this->is_active ? 'Actif' : 'Inactif';
    }

    /**
     * Classe CSS pour le statut
     */
    public function getStatusClassAttribute(): string
    {
        return $this->is_active ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100';
    }

    /**
     * Dernière connexion formatée
     */
    public function getLastLoginFormattedAttribute(): string
    {
        return $this->last_login_at ? $this->last_login_at->format('d/m/Y H:i') : 'Jamais';
    }

    /**
     * Date de création formatée
     */
    public function getCreatedAtFormattedAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    // ============ SCOPES ============

    /**
     * Utilisateurs actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Utilisateurs inactifs
     */
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    /**
     * Utilisateurs par rôle
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Super admins uniquement
     */
    public function scopeSuperAdmins($query)
    {
        return $query->where('role', 'super_admin');
    }

    /**
     * Admins uniquement
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Caissiers uniquement
     */
    public function scopeCashiers($query)
    {
        return $query->where('role', 'cashier');
    }

    /**
     * Magasiniers uniquement
     */
    public function scopeStorekeepers($query)
    {
        return $query->where('role', 'storekeeper');
    }

    /**
     * Gestionnaires uniquement
     */
    public function scopeManagers($query)
    {
        return $query->where('role', 'manager');
    }

    /**
     * Utilisateurs qui peuvent gérer d'autres utilisateurs
     */
    public function scopeCanManageUsers($query)
    {
        return $query->where('can_manage_users', true);
    }

    /**
     * Recherche par nom ou email
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('email', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Utilisateurs qui se sont connectés récemment
     */
    public function scopeRecentlyActive($query, $days = 7)
    {
        return $query->where('last_login_at', '>=', now()->subDays($days));
    }

    // ============ MÉTHODES ============

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole($role): bool
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    /**
     * Vérifier si l'utilisateur est super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    /**
     * Vérifier si l'utilisateur est admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->role === 'super_admin';
    }

    /**
     * Vérifier si l'utilisateur peut gérer les utilisateurs
     */
    public function canManageUsers(): bool
    {
        return $this->can_manage_users || $this->isAdmin();
    }

    /**
     * Vérifier si l'utilisateur peut gérer le stock
     */
    public function canManageStock(): bool
    {
        return $this->can_manage_stock || $this->isAdmin();
    }

    /**
     * Vérifier si l'utilisateur peut gérer les ventes
     */
    public function canManageSales(): bool
    {
        return $this->can_manage_sales || $this->isAdmin();
    }

    /**
     * Vérifier si l'utilisateur peut gérer les clients
     */
    public function canManageClients(): bool
    {
        return $this->can_manage_clients || $this->isAdmin();
    }

    /**
     * Vérifier si l'utilisateur peut voir les rapports
     */
    public function canViewReports(): bool
    {
        return $this->can_view_reports || $this->isAdmin();
    }

    /**
     * Enregistrer la dernière connexion
     */
    public function recordLogin($ip = null): self
    {
        $this->last_login_at = now();
        $this->last_login_ip = $ip;
        $this->save();

        return $this;
    }

    /**
     * Vérifier si le mot de passe correspond
     */
    public function verifyPassword($password): bool
    {
        return Hash::check($password, $this->password);
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword($password): self
    {
        $this->password = Hash::make($password);
        $this->save();

        return $this;
    }

    /**
     * Activer l'utilisateur
     */
    public function activate(): self
    {
        $this->is_active = true;
        $this->save();

        return $this;
    }

    /**
     * Désactiver l'utilisateur
     */
    public function deactivate(): self
    {
        $this->is_active = false;
        $this->save();

        return $this;
    }

    /**
     * Récupérer les statistiques de l'utilisateur
     */
    public function getStatistics(): array
    {
        return [
            'total_sales' => $this->sales()->count(),
            'total_sales_amount' => $this->sales()->sum('final_price'),
            'formatted_sales_amount' => number_format($this->sales()->sum('final_price'), 0, ',', ' ') . ' FCFA',
            'total_stock_movements' => $this->stockMovements()->count(),
            'total_products_created' => $this->products()->count(),
            'total_clients_created' => $this->clients()->count(),
            'total_suppliers_created' => $this->suppliers()->count(),
            'average_sale_value' => $this->sales()->avg('final_price') ?? 0,
            'last_sale_date' => $this->sales()->latest()->first()?->created_at?->format('d/m/Y H:i'),
            'sales_this_month' => $this->sales()->whereMonth('created_at', now()->month)->count(),
            'sales_today' => $this->sales()->whereDate('created_at', today())->count(),
        ];
    }

    /**
     * Vérifier si l'utilisateur peut être supprimé
     */
    public function canBeDeleted(): bool
    {
        // Ne pas supprimer le super admin principal
        if ($this->isSuperAdmin() && !$this->createdUsers()->exists()) {
            return false;
        }

        // Vérifier s'il a des données associées
        $hasData = $this->sales()->exists() ||
                   $this->stockMovements()->exists() ||
                   $this->products()->exists() ||
                   $this->clients()->exists() ||
                   $this->suppliers()->exists();

        return !$hasData;
    }

    /**
     * Récupérer le rapport complet de l'utilisateur
     */
    public function getFullReport(): array
    {
        $stats = $this->getStatistics();

        return [
            'user_info' => [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->role_label,
                'status' => $this->status_label,
                'created_at' => $this->created_at_formatted,
                'last_login' => $this->last_login_formatted,
                'last_login_ip' => $this->last_login_ip,
            ],
            'permissions' => [
                'can_manage_users' => $this->canManageUsers(),
                'can_manage_stock' => $this->canManageStock(),
                'can_manage_sales' => $this->canManageSales(),
                'can_manage_clients' => $this->canManageClients(),
                'can_view_reports' => $this->canViewReports(),
            ],
            'statistics' => $stats,
            'recent_sales' => $this->sales()->with('client')->latest()->limit(10)->get()->map(function($sale) {
                return [
                    'id' => $sale->id,
                    'invoice' => $sale->invoice_number,
                    'client' => $sale->client?->name ?? 'N/A',
                    'amount' => number_format($sale->final_price, 0, ',', ' ') . ' FCFA',
                    'date' => $sale->created_at->format('d/m/Y H:i'),
                ];
            }),
        ];
    }

    /**
     * Récupérer les statistiques globales des utilisateurs du tenant
     * Version optimisée pour PostgreSQL
     */
    public static function getGlobalUserStats($tenantId = null): array
    {
        $query = self::query();

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        $total = (clone $query)->count();
        $active = (clone $query)->active()->count();
        $inactive = (clone $query)->inactive()->count();

        $statsByRole = (clone $query)
            ->select('role', DB::raw('COUNT(*) as count'))
            ->groupBy('role')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->role => $item->count];
            });

        $recentlyActive = (clone $query)->recentlyActive(7)->count();

        return [
            'total_users' => $total,
            'active_users' => $active,
            'inactive_users' => $inactive,
            'recently_active' => $recentlyActive,
            'stats_by_role' => $statsByRole,
            'super_admins' => $statsByRole['super_admin'] ?? 0,
            'admins' => $statsByRole['admin'] ?? 0,
            'managers' => $statsByRole['manager'] ?? 0,
            'cashiers' => $statsByRole['cashier'] ?? 0,
            'storekeepers' => $statsByRole['storekeeper'] ?? 0,
        ];
    }

    /**
     * Récupérer les utilisateurs les plus actifs
     */
    public static function getTopActiveUsers($limit = 10, $tenantId = null)
    {
        $query = self::withCount(['sales', 'stockMovements'])
            ->orderBy('sales_count', 'desc')
            ->orderBy('stock_movements_count', 'desc')
            ->limit($limit);

        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }

        return $query->get()->map(function($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'role' => $user->role_label,
                'sales_count' => $user->sales_count,
                'stock_movements_count' => $user->stock_movements_count,
                'last_active' => $user->last_login_formatted,
            ];
        });
    }
}