<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'tenant_id',
        'owner_id',
        'can_manage_users',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'can_manage_users' => 'boolean',
        ];
    }

    /**
     * =====================================================
     * RELATIONS
     * =====================================================
     */
    
    /**
     * La quincaillerie (tenant) auquel appartient l'utilisateur
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Le propriétaire (super_admin) qui a créé cet utilisateur
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Les employés créés par cet utilisateur (si c'est un super_admin)
     */
    public function employees()
    {
        return $this->hasMany(User::class, 'owner_id');
    }

    /**
     * =====================================================
     * VÉRIFICATIONS DE RÔLE
     * =====================================================
     */
    public function isSuperAdminGlobal(): bool
    {
        return $this->role === 'super_admin_global';
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    public function isStorekeeper(): bool
    {
        return $this->role === 'storekeeper';
    }

    /**
     * =====================================================
     * VÉRIFICATIONS DE RÔLES ÉTENDUS
     * =====================================================
     */
    public function isSuperAdminOrAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function isManagerOrAbove(): bool
    {
        return $this->isSuperAdminGlobal() || 
               $this->isSuperAdmin() || 
               $this->isAdmin() || 
               $this->isManager();
    }

    /**
     * =====================================================
     * PERMISSIONS SPÉCIFIQUES
     * =====================================================
     */
    public function canManageUsers(): bool
    {
        return $this->isSuperAdminGlobal() || 
               $this->isSuperAdmin() || 
               ($this->isAdmin() && $this->can_manage_users);
    }

    public function canManageStock(): bool
    {
        return $this->isSuperAdminGlobal() || 
               $this->isSuperAdmin() || 
               $this->isAdmin() || 
               $this->isManager() || 
               $this->isStorekeeper();
    }

    public function canManageSales(): bool
    {
        return $this->isSuperAdminGlobal() || 
               $this->isSuperAdmin() || 
               $this->isAdmin() || 
               $this->isManager() || 
               $this->isCashier();
    }

    public function canViewReports(): bool
    {
        return $this->isSuperAdminGlobal() || 
               $this->isSuperAdmin() || 
               $this->isAdmin() || 
               $this->isManager();
    }

    /**
     * =====================================================
     * PERMISSIONS SPÉCIFIQUES AU SUPER ADMIN GLOBAL
     * =====================================================
     */
    public function canViewAllTenants(): bool
    {
        return $this->isSuperAdminGlobal();
    }

    public function canManageSubscriptions(): bool
    {
        return $this->isSuperAdminGlobal();
    }

    /**
     * Vérifie si l'utilisateur a accès à une ressource spécifique
     */
    public function hasAccessTo($resource): bool
    {
        // Le super_admin_global a accès à tout
        if ($this->isSuperAdminGlobal()) {
            return true;
        }
        
        if (!$resource || !$resource->owner_id) {
            return false;
        }
        
        return $this->id === $resource->owner_id || 
               $this->owner_id === $resource->owner_id ||
               $this->isSuperAdmin();
    }

    /**
     * Récupère le propriétaire racine (super_admin)
     */
    public function getRootOwnerAttribute()
    {
        if ($this->isSuperAdminGlobal()) {
            return null; // Le global n'a pas de propriétaire
        }
        
        if ($this->isSuperAdmin()) {
            return $this;
        }
        
        return $this->owner;
    }

    /**
     * =====================================================
     * SCOPES
     * =====================================================
     */
    
    /**
     * Récupère tous les utilisateurs de la même quincaillerie
     * ou tous les utilisateurs pour le super_admin_global
     */
    public function scopeSameCompany($query)
    {
        if ($this->isSuperAdminGlobal()) {
            return $query; // Le super_admin_global voit tout
        }
        
        $ownerId = $this->isSuperAdmin() ? $this->id : $this->owner_id;
        return $query->where('owner_id', $ownerId);
    }

    /**
     * Scope pour les utilisateurs d'un tenant spécifique
     */
    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    /**
     * Scope pour les utilisateurs d'un rôle spécifique
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * =====================================================
     * MÉTHODES UTILITAIRES
     * =====================================================
     */

    /**
     * Récupère tous les tenants (quincailleries) accessibles
     */
    public function getAccessibleTenants()
    {
        if ($this->isSuperAdminGlobal()) {
            return \App\Models\Tenant::all();
        }
        
        return \App\Models\Tenant::where('id', $this->tenant_id)->get();
    }

    /**
     * Récupère le nom du rôle en français
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
            'super_admin_global' => 'Super Admin Global',
            'super_admin' => 'Super Admin',
            'admin' => 'Administrateur',
            'manager' => 'Gérant',
            'cashier' => 'Caissier',
            'storekeeper' => 'Magasinier',
            default => 'Employé'
        };
    }

    /**
     * Récupère la couleur du badge pour le rôle
     */
    public function getRoleColorAttribute(): string
    {
        return match($this->role) {
            'super_admin_global' => 'purple',
            'super_admin' => 'violet',
            'admin' => 'red',
            'manager' => 'blue',
            'cashier' => 'green',
            'storekeeper' => 'orange',
            default => 'gray'
        };
    }

    /**
     * Vérifie si l'utilisateur est le propriétaire global (toi)
     */
    public function isGlobalOwner(): bool
    {
        // Tu peux définir ton email ici pour identification
        return $this->email === 'admin@quincaapp.com' || $this->isSuperAdminGlobal();
    }

    /**
     * Récupère le nombre d'employés (si c'est un super_admin)
     */
    public function getEmployeesCountAttribute(): int
    {
        if ($this->isSuperAdmin()) {
            return $this->employees()->count();
        }
        return 0;
    }

    /**
     * Vérifie si l'utilisateur peut accéder au dashboard
     */
    public function canAccessDashboard(): bool
    {
        return true; // Tous les utilisateurs connectés peuvent accéder au dashboard
    }

    /**
     * Récupère le dashboard approprié selon le rôle
     */
    public function getDashboardRoute(): string
    {
        if ($this->isSuperAdminGlobal()) {
            return route('super-admin.dashboard');
        }
        
        return route('dashboard');
    }

    /**
     * =====================================================
     * MÉTHODES POUR L'ABONNEMENT
     * =====================================================
     */

    /**
     * Vérifie si le tenant de l'utilisateur a un abonnement actif
     */
    public function hasActiveTenantSubscription(): bool
    {
        if ($this->isSuperAdminGlobal()) {
            return true; // Le global n'est pas concerné
        }
        
        return $this->tenant && $this->tenant->hasActiveSubscription();
    }

    /**
     * Récupère les jours restants de l'abonnement du tenant
     */
    public function getTenantDaysRemainingAttribute(): int
    {
        if ($this->isSuperAdminGlobal() || !$this->tenant) {
            return 365; // Valeur par défaut
        }
        
        return $this->tenant->daysRemaining();
    }
}