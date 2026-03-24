<?php

namespace App\Models;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory, TenantScope;

    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'owner_id',
        'tenant_id',
    ];

    // ============ RELATIONS ============
    
    /**
     * Relation avec les produits
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relation parent (catégorie parente)
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * Relation enfants (sous-catégories)
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Propriétaire (super_admin)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Tenant (quincaillerie)
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    // ============ MÉTHODES EXISTANTES ============

    /**
     * Récupérer tous les produits de la catégorie et de ses sous-catégories
     */
    public function getAllProducts()
    {
        $products = $this->products;
        
        foreach ($this->children as $child) {
            $products = $products->merge($child->getAllProducts());
        }
        
        return $products;
    }

    /**
     * Compter tous les produits dans la catégorie et ses sous-catégories
     */
    public function getTotalProductsWithDescendants()
    {
        $total = $this->products->count();
        
        foreach ($this->children as $child) {
            $total += $child->getTotalProductsWithDescendants();
        }
        
        return $total;
    }

    /**
     * Vérifier si c'est une catégorie principale
     */
    public function isMainCategory()
    {
        return is_null($this->parent_id);
    }

    /**
     * Vérifier si c'est une sous-catégorie
     */
    public function isSubCategory()
    {
        return !is_null($this->parent_id);
    }

    /**
     * Récupérer le chemin complet de la catégorie
     */
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

    // ============ NOUVELLES MÉTHODES ============

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
     * Scope pour les catégories de la même quincaillerie
     */
    public function scopeSameCompany($query)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Super Admin Global voit tout
            if (method_exists($user, 'isSuperAdminGlobal') && $user->isSuperAdminGlobal()) {
                return $query;
            }
            
            return $query->where('tenant_id', $user->tenant_id);
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
     * Scope pour les catégories principales (sans parent)
     */
    public function scopeMainCategories($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope pour les sous-catégories (avec parent)
     */
    public function scopeSubCategories($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Récupérer le nombre de produits (version optimisée)
     */
    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }

    /**
     * Récupérer le nombre total de produits avec descendants (version optimisée pour PostgreSQL)
     */
    public function getTotalProductsCountAttribute()
    {
        $count = $this->products()->count();
        
        foreach ($this->children as $child) {
            $count += $child->total_products_count;
        }
        
        return $count;
    }

    /**
     * Récupérer les catégories avec leur nombre de produits
     * Version optimisée pour PostgreSQL
     */
    public static function getCategoriesWithProductCount($tenantId = null)
    {
        $query = self::withCount('products')
            ->with(['children' => function($q) {
                $q->withCount('products');
            }]);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->mainCategories()->get();
    }

    /**
     * Récupérer l'arborescence complète des catégories
     */
    public static function getCategoryTree($tenantId = null)
    {
        $query = self::with(['children' => function($q) {
            $q->with('children');
        }]);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->mainCategories()->get();
    }

    /**
     * Vérifier si la catégorie a des produits
     */
    public function hasProducts()
    {
        return $this->products()->exists();
    }

    /**
     * Vérifier si la catégorie a des sous-catégories
     */
    public function hasChildren()
    {
        return $this->children()->exists();
    }

    /**
     * Supprimer la catégorie et ses sous-catégories (avec vérification)
     */
    public function deleteWithChildren()
    {
        // Vérifier si des produits existent dans cette catégorie ou ses sous-catégories
        $hasProducts = $this->hasProducts();
        
        if ($hasProducts) {
            throw new \Exception("Impossible de supprimer la catégorie car elle contient des produits.");
        }
        
        // Supprimer récursivement les sous-catégories
        foreach ($this->children as $child) {
            $child->deleteWithChildren();
        }
        
        // Supprimer la catégorie elle-même
        return $this->delete();
    }

    /**
     * Déplacer tous les produits d'une catégorie vers une autre
     */
    public function moveProductsTo(Category $targetCategory)
    {
        if ($targetCategory->tenant_id != $this->tenant_id) {
            throw new \Exception("Impossible de déplacer les produits vers une catégorie d'une autre quincaillerie.");
        }
        
        return $this->products()->update(['category_id' => $targetCategory->id]);
    }

    /**
     * Récupérer les statistiques avancées de la catégorie
     * Version optimisée pour PostgreSQL
     */
    public function getAdvancedStats()
    {
        $stats = [
            'basic' => $this->stats,
            'products' => [
                'total' => $this->products()->count(),
                'in_stock' => $this->products()->inStock()->count(),
                'out_of_stock' => $this->products()->outOfStock()->count(),
                'low_stock' => $this->products()->lowStock()->count(),
            ],
            'value' => [
                'total_stock_value' => $this->products()->sum(DB::raw('stock * sale_price')),
                'total_purchase_value' => $this->products()->sum(DB::raw('stock * purchase_price')),
            ],
        ];
        
        return $stats;
    }

    /**
     * Récupérer les produits les plus vendus de la catégorie
     */
    public function getTopSellingProducts($limit = 5)
    {
        return $this->products()
            ->select('products.*', DB::raw('SUM(sale_items.quantity) as total_sold'))
            ->join('sale_items', 'products.id', '=', 'sale_items.product_id')
            ->where('sale_items.tenant_id', $this->tenant_id)
            ->groupBy('products.id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();
    }
}