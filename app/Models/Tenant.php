<?php
// app/Models/Tenant.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'company_name',
        'domain',
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
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'subscription_ends_at' => 'datetime',
        'settings' => 'array',
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
     * Vérifier si le tenant est actif
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
}