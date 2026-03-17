<?php

namespace App\Models;

use App\Traits\TenantScope; // ← AJOUTER
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory, TenantScope; // ← AJOUTER TenantScope

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'tenant_id',
    ];

    // ============ RELATIONS ============
    
    // Relation avec les produits
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Relation parent (catégorie parente)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Relation enfants (sous-catégories)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // 👇 NOUVELLE RELATION : Propriétaire (super_admin)
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // ============ MÉTHODES EXISTANTES (inchangées) ============

    // Récupérer tous les produits de la catégorie et de ses sous-catégories
    public function getAllProducts()
    {
        // Récupérer tous les produits de cette catégorie
        $products = $this->products;
        
        // Récupérer récursivement les produits des sous-catégories
        foreach ($this->children as $child) {
            $products = $products->merge($child->getAllProducts());
        }
        
        return $products;
    }

    // Compter tous les produits dans la catégorie et ses sous-catégories
    public function getTotalProductsWithDescendants()
    {
        $total = $this->products->count();
        
        foreach ($this->children as $child) {
            $total += $child->getTotalProductsWithDescendants();
        }
        
        return $total;
    }

    // Vérifier si c'est une catégorie principale
    public function isMainCategory()
    {
        return is_null($this->parent_id);
    }

    // Vérifier si c'est une sous-catégorie
    public function isSubCategory()
    {
        return !is_null($this->parent_id);
    }

    // Récupérer le chemin complet de la catégorie
    public function getFullPath()
    {
        $path = [$this->name];
        $parent = $this->parent;
        
        while ($parent) {
            array_unshift($path, $parent->name);
            $parent = $parent->parent;
        }
        
        return implode(' → ', $path);
    }

    // ============ NOUVELLES MÉTHODES UTILES ============

    /**
     * Récupérer toutes les sous-catégories récursivement
     */
    public function getAllDescendants()
    {
        $descendants = collect();
        
        foreach ($this->children as $child) {
            $descendants->push($child);
            $descendants = $descendants->merge($child->getAllDescendants());
        }
        
        return $descendants;
    }

    /**
     * Récupérer toutes les catégories de la même quincaillerie
     */
    public function scopeSameCompany($query)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $ownerId = $user->isSuperAdmin() ? $user->id : $user->owner_id;
            return $query->where('owner_id', $ownerId);
        }
        return $query;
    }

    /**
     * Récupérer les statistiques de la catégorie
     */
    public function getStatsAttribute()
    {
        return [
            'total_products' => $this->getTotalProductsWithDescendants(),
            'total_subcategories' => $this->children->count(),
            'total_descendants' => $this->getAllDescendants()->count(),
            'is_main' => $this->isMainCategory(),
            'is_sub' => $this->isSubCategory(),
            'path' => $this->getFullPath(),
        ];
    }

    /**
     * Récupérer les catégories principales (sans parent)
     */
    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Récupérer les sous-catégories (avec parent)
     */
    public function scopeSubCategories($query)
    {
        return $query->whereNotNull('parent_id');
    }
}