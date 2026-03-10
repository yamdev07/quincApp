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
}