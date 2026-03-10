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
     * RELATIONS
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'owner_id');
    }

    /**
     * VÉRIFICATIONS DE RÔLE
     */
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
     * VÉRIFICATIONS DE RÔLES ÉTENDUS
     */
    public function isSuperAdminOrAdmin(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin();
    }

    public function isManagerOrAbove(): bool
    {
        return $this->isSuperAdmin() || $this->isAdmin() || $this->isManager();
    }

    /**
     * PERMISSIONS SPÉCIFIQUES
     */
    public function canManageUsers(): bool
    {
        return $this->isSuperAdmin() || $this->can_manage_users;
    }

    public function canManageStock(): bool
    {
        return $this->isSuperAdmin() || 
               $this->isAdmin() || 
               $this->isManager() || 
               $this->isStorekeeper();
    }

    public function canManageSales(): bool
    {
        return $this->isSuperAdmin() || 
               $this->isAdmin() || 
               $this->isManager() || 
               $this->isCashier();
    }

    public function canViewReports(): bool
    {
        return $this->isSuperAdmin() || 
               $this->isAdmin() || 
               $this->isManager();
    }

    /**
     * Vérifie si l'utilisateur a accès à une ressource spécifique
     */
    public function hasAccessTo($resource): bool
    {
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
        if ($this->isSuperAdmin()) {
            return $this;
        }
        
        return $this->owner;
    }

    /**
     * Récupère tous les utilisateurs de la même quincaillerie
     */
    public function scopeSameCompany($query)
    {
        $ownerId = $this->isSuperAdmin() ? $this->id : $this->owner_id;
        return $query->where('owner_id', $ownerId);
    }

    /**
     * Récupère le nom du rôle en français
     */
    public function getRoleLabelAttribute(): string
    {
        return match($this->role) {
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
            'super_admin' => 'purple',
            'admin' => 'red',
            'manager' => 'blue',
            'cashier' => 'green',
            'storekeeper' => 'orange',
            default => 'gray'
        };
    }
}