<?php
// app/Traits/TenantScope.php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

trait TenantScope
{
    /**
     * Boot the tenant scope trait.
     */
    protected static function bootTenantScope()
    {
        // Appliquer automatiquement le filtre sur TOUTES les requêtes
        static::addGlobalScope('tenant', function (Builder $builder) {
            if (Auth::check()) {
                $user = Auth::user();
                $ownerId = $user->isSuperAdmin() ? $user->id : ($user->owner_id ?? $user->id);
                
                $builder->where($builder->getModel()->getTable() . '.owner_id', $ownerId);
            }
        });

        // Assigner automatiquement owner_id à la création
        static::creating(function ($model) {
            if (Auth::check()) {
                $user = Auth::user();
                $model->owner_id = $user->isSuperAdmin() ? $user->id : ($user->owner_id ?? $user->id);
            }
        });
    }

    /**
     * Désactiver temporairement le scope tenant (pour admin)
     */
    public function scopeWithoutTenant($query)
    {
        return $query->withoutGlobalScope('tenant');
    }

    /**
     * Voir toutes les données (admin uniquement)
     */
    public function scopeAllData($query)
    {
        if (Auth::check() && Auth::user()->isSuperAdmin()) {
            return $query->withoutGlobalScope('tenant');
        }
        return $query;
    }

    /**
     * Vérifier si l'utilisateur a accès à ce modèle
     */
    public function isAccessibleBy($user = null)
    {
        $user = $user ?? Auth::user();
        
        if (!$user) return false;
        
        return $this->owner_id === ($user->owner_id ?? $user->id) || 
               $user->isSuperAdmin();
    }

    /**
     * Relation avec le propriétaire
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}