<?php

namespace App\Models;

use App\Traits\TenantScope; // ← AJOUTER
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory, TenantScope; // ← AJOUTER TenantScope

    protected $fillable = [
        'name',
        'contact',
        'phone',
        'address',
        // 'owner_id' n'est PAS dans fillable (sera auto-assigné)
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============ RELATIONS ============

    /**
     * Tous les produits de ce fournisseur
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * 👇 Propriétaire (super_admin de la quincaillerie)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // ============ ACCESSORS ============

    /**
     * Nombre de produits
     */
    public function getProductsCountAttribute(): int
    {
        return $this->products()->count();
    }

    /**
     * Valeur totale du stock des produits de ce fournisseur
     */
    public function getTotalStockValueAttribute(): float
    {
        return $this->products->sum(function($product) {
            return $product->stock * $product->purchase_price;
        });
    }

    /**
     * Valeur totale formatée
     */
    public function getFormattedTotalStockValueAttribute(): string
    {
        return number_format($this->total_stock_value, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Initiales du fournisseur
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
     * Téléphone formaté
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        if (!$this->phone) return null;
        
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        
        if (strlen($phone) === 8) {
            return substr($phone, 0, 2) . ' ' . substr($phone, 2, 2) . ' ' . 
                   substr($phone, 4, 2) . ' ' . substr($phone, 6, 2);
        } elseif (strlen($phone) === 9) {
            return substr($phone, 0, 3) . ' ' . substr($phone, 3, 2) . ' ' . 
                   substr($phone, 5, 2) . ' ' . substr($phone, 7, 2);
        }
        
        return $phone;
    }

    /**
     * Nom du contact
     */
    public function getContactNameAttribute(): ?string
    {
        return $this->contact ?? 'Non spécifié';
    }

    /**
     * Statut (actif/inactif) - basé sur l'existence de produits
     */
    public function getStatusAttribute(): string
    {
        return $this->products_count > 0 ? 'Actif' : 'Inactif';
    }

    /**
     * Classe de couleur pour le statut
     */
    public function getStatusClassAttribute(): string
    {
        return $this->products_count > 0 ? 'text-green-600 bg-green-100' : 'text-gray-600 bg-gray-100';
    }

    /**
     * Dernière commande (date)
     */
    public function getLastOrderDateAttribute(): ?string
    {
        $lastMovement = StockMovement::whereHas('product', function($query) {
            $query->where('supplier_id', $this->id);
        })->latest()->first();
        
        return $lastMovement ? $lastMovement->created_at->format('d/m/Y') : null;
    }

    // ============ SCOPES ============

    /**
     * 👇 Filtrer les fournisseurs de la même quincaillerie
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
     * Fournisseurs avec produits
     */
    public function scopeWithProducts($query)
    {
        return $query->has('products', '>', 0);
    }

    /**
     * Fournisseurs sans produits
     */
    public function scopeWithoutProducts($query)
    {
        return $query->has('products', '=', 0);
    }

    /**
     * Fournisseurs avec téléphone
     */
    public function scopeWithPhone($query)
    {
        return $query->whereNotNull('phone')->where('phone', '!=', '');
    }

    /**
     * Fournisseurs avec contact
     */
    public function scopeWithContact($query)
    {
        return $query->whereNotNull('contact')->where('contact', '!=', '');
    }

    /**
     * Fournisseurs avec adresse
     */
    public function scopeWithAddress($query)
    {
        return $query->whereNotNull('address')->where('address', '!=', '');
    }

    /**
     * Recherche par nom
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('contact', 'LIKE', "%{$term}%")
              ->orWhere('phone', 'LIKE', "%{$term}%");
        });
    }

    /**
     * Fournisseurs avec stock minimum
     */
    public function scopeMinStockValue($query, $value)
    {
        return $query->whereHas('products', function($q) use ($value) {
            $q->havingRaw('SUM(stock * purchase_price) >= ?', [$value]);
        });
    }

    // ============ MÉTHODES ============

    /**
     * Récupérer les statistiques du fournisseur
     */
    public function getStatistics(): array
    {
        $products = $this->products;
        $totalStock = $products->sum('stock');
        $totalValue = $products->sum(function($p) {
            return $p->stock * $p->purchase_price;
        });
        
        $outOfStock = $products->where('stock', 0)->count();
        $lowStock = $products->filter(function($p) {
            return $p->stock > 0 && $p->stock <= 5;
        })->count();
        
        return [
            'total_products' => $products->count(),
            'total_stock_units' => $totalStock,
            'total_stock_value' => $totalValue,
            'formatted_total_value' => number_format($totalValue, 0, ',', ' ') . ' FCFA',
            'out_of_stock' => $outOfStock,
            'low_stock' => $lowStock,
            'active_products' => $products->where('stock', '>', 0)->count(),
            'average_stock_per_product' => $products->count() > 0 ? round($totalStock / $products->count(), 1) : 0,
            'most_expensive_product' => $products->sortByDesc('purchase_price')->first()?->name ?? 'N/A',
            'most_stocked_product' => $products->sortByDesc('stock')->first()?->name ?? 'N/A',
        ];
    }

    /**
     * Récupérer tous les mouvements de stock des produits de ce fournisseur
     */
    public function getStockMovements()
    {
        return StockMovement::whereHas('product', function($query) {
            $query->where('supplier_id', $this->id);
        })->with('product')->latest()->get();
    }

    /**
     * Récupérer les produits en rupture de stock
     */
    public function getOutOfStockProducts()
    {
        return $this->products()->where('stock', 0)->get();
    }

    /**
     * Récupérer les produits en faible stock
     */
    public function getLowStockProducts($threshold = 5)
    {
        return $this->products()->where('stock', '>', 0)
                                 ->where('stock', '<=', $threshold)
                                 ->get();
    }

    /**
     * Vérifier si le fournisseur a des produits
     */
    public function hasProducts(): bool
    {
        return $this->products()->count() > 0;
    }

    /**
     * Mettre à jour les coordonnées
     */
    public function updateContactInfo(array $data)
    {
        $this->update([
            'contact' => $data['contact'] ?? $this->contact,
            'phone' => $data['phone'] ?? $this->phone,
            'address' => $data['address'] ?? $this->address,
        ]);
        
        return $this;
    }

    /**
     * Récupérer le rapport complet du fournisseur
     */
    public function getFullReport(): array
    {
        return [
            'supplier_info' => [
                'id' => $this->id,
                'name' => $this->name,
                'contact' => $this->contact_name,
                'phone' => $this->formatted_phone,
                'address' => $this->address,
                'created_at' => $this->created_at->format('d/m/Y'),
                'status' => $this->status,
            ],
            'statistics' => $this->getStatistics(),
            'products' => $this->products()->with('category')->get()->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'stock' => $product->stock,
                    'purchase_price' => number_format($product->purchase_price, 0, ',', ' ') . ' FCFA',
                    'sale_price' => number_format($product->sale_price, 0, ',', ' ') . ' FCFA',
                    'category' => $product->category?->name,
                    'status' => $product->stock_status,
                ];
            }),
            'recent_movements' => $this->getStockMovements()->take(10)->map(function($movement) {
                return [
                    'date' => $movement->created_at->format('d/m/Y H:i'),
                    'product' => $movement->product?->name,
                    'type' => $movement->type_label,
                    'quantity' => $movement->quantity,
                    'motif' => $movement->motif,
                ];
            }),
        ];
    }

    /**
     * Scope pour la recherche avancée
     */
    public function scopeAdvancedSearch($query, array $filters)
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'LIKE', "%{$filters['name']}%");
        }
        
        if (!empty($filters['contact'])) {
            $query->where('contact', 'LIKE', "%{$filters['contact']}%");
        }
        
        if (!empty($filters['phone'])) {
            $query->where('phone', 'LIKE', "%{$filters['phone']}%");
        }
        
        if (!empty($filters['has_products'])) {
            $filters['has_products'] === 'yes' 
                ? $query->has('products', '>', 0)
                : $query->has('products', '=', 0);
        }
        
        if (!empty($filters['created_from'])) {
            $query->whereDate('created_at', '>=', $filters['created_from']);
        }
        
        if (!empty($filters['created_to'])) {
            $query->whereDate('created_at', '<=', $filters['created_to']);
        }
        
        return $query;
    }
}