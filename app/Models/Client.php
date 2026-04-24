<?php

namespace App\Models;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Client extends Model
{
    use HasFactory, TenantScope, SoftDeletes;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'owner_id',
        'tenant_id',
    ];

    // ============ RELATIONS ============

    /**
     * Toutes les ventes de ce client
     */
    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    /**
     * Propriétaire (super_admin de la quincaillerie)
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

    // ============ ACCESSORS ============

    /**
     * Nombre total de ventes
     */
    public function getTotalSalesAttribute()
    {
        return $this->sales()->count();
    }

    /**
     * Montant total des achats
     */
    public function getTotalSpentAttribute()
    {
        return $this->sales()->sum('total_price');
    }

    /**
     * Dernière date d'achat
     */
    public function getLastPurchaseAttribute()
    {
        $lastSale = $this->sales()->latest()->first();
        return $lastSale ? $lastSale->created_at->format('d/m/Y') : 'Jamais';
    }

    /**
     * Statut du client (fidèle, régulier, nouveau)
     */
    public function getStatusAttribute()
    {
        $totalSales = $this->total_sales;
        
        if ($totalSales >= 10) {
            return 'fidèle';
        } elseif ($totalSales >= 3) {
            return 'régulier';
        } elseif ($totalSales >= 1) {
            return 'occasionnel';
        } else {
            return 'nouveau';
        }
    }

    /**
     * Initiales du client
     */
    public function getInitialsAttribute()
    {
        $words = explode(' ', $this->name);
        $initials = '';
        
        foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }

    // ============ SCOPES ============

    /**
     * Filtrer les clients de la même quincaillerie (par tenant_id)
     */
    public function scopeSameCompany($query)
    {
        if (auth()->check()) {
            $user = auth()->user();
            
            // Super Admin Global voit tout
            if ($user->isSuperAdminGlobal()) {
                return $query;
            }
            
            return $query->where('tenant_id', $user->tenant_id);
        }
        return $query;
    }

    /**
     * Clients avec téléphone
     */
    public function scopeWithPhone($query)
    {
        return $query->whereNotNull('phone')->where('phone', '!=', '');
    }

    /**
     * Clients avec email
     */
    public function scopeWithEmail($query)
    {
        return $query->whereNotNull('email')->where('email', '!=', '');
    }

    /**
     * Clients sans téléphone ni email
     */
    public function scopeWithoutContact($query)
    {
        return $query->where(function($q) {
            $q->whereNull('phone')->orWhere('phone', '');
        })->where(function($q) {
            $q->whereNull('email')->orWhere('email', '');
        });
    }

    /**
     * Clients fidèles - Version compatible PostgreSQL
     */
    public function scopeLoyal($query, $threshold = 10)
    {
        return $query->whereHas('sales', function($q) use ($threshold) {
            $q->select(DB::raw('count(*) as total'))
              ->havingRaw('count(*) >= ?', [$threshold]);
        });
    }

    /**
     * Clients réguliers - Version compatible PostgreSQL
     */
    public function scopeRegular($query, $min = 3, $max = 9)
    {
        return $query->whereHas('sales', function($q) use ($min, $max) {
            $q->select(DB::raw('count(*) as total'))
              ->havingRaw('count(*) >= ? AND count(*) <= ?', [$min, $max]);
        });
    }

    /**
     * Clients actifs (ont acheté récemment) - Version compatible PostgreSQL
     */
    public function scopeActive($query, $days = 30)
    {
        return $query->whereHas('sales', function($q) use ($days) {
            $q->where('created_at', '>=', now()->subDays($days));
        });
    }

    // ============ MÉTHODES ============

    /**
     * Vérifier si le client a acheté un produit spécifique
     */
    public function hasPurchased(Product $product)
    {
        return $this->sales()
            ->whereHas('items', function($q) use ($product) {
                $q->where('product_id', $product->id);
            })->exists();
    }

    /**
     * Récupérer les produits préférés du client - Version optimisée
     */
    public function favoriteProducts($limit = 5)
    {
        // Version optimisée avec une seule requête
        $productIds = DB::table('sales')
            ->join('sale_items', 'sales.id', '=', 'sale_items.sale_id')
            ->where('sales.client_id', $this->id)
            ->select('sale_items.product_id', DB::raw('COUNT(*) as purchase_count'))
            ->groupBy('sale_items.product_id')
            ->orderBy('purchase_count', 'desc')
            ->limit($limit)
            ->pluck('product_id');
        
        if ($productIds->isEmpty()) {
            return collect();
        }
        
        return Product::whereIn('id', $productIds)
            ->with(['category', 'supplier'])
            ->get();
    }

    /**
     * Récupérer les statistiques complètes du client
     */
    public function getStatistics()
    {
        $sales = $this->sales;
        $totalSpent = $sales->sum('total_price');
        $averageCart = $sales->avg('total_price') ?? 0;
        
        return [
            'total_sales' => $sales->count(),
            'total_spent' => $totalSpent,
            'average_cart' => $averageCart,
            'first_purchase' => $sales->min('created_at')?->format('d/m/Y'),
            'last_purchase' => $sales->max('created_at')?->format('d/m/Y'),
            'status' => $this->status,
            'favorite_products' => $this->favoriteProducts(3),
        ];
    }

    /**
     * Formater le numéro de téléphone
     */
    public function getFormattedPhoneAttribute()
    {
        if (!$this->phone) return null;
        
        // Nettoyer le numéro
        $phone = preg_replace('/[^0-9]/', '', $this->phone);
        
        // Formater selon la longueur
        if (strlen($phone) === 8) {
            return substr($phone, 0, 2) . ' ' . substr($phone, 2, 2) . ' ' . 
                   substr($phone, 4, 2) . ' ' . substr($phone, 6, 2);
        } elseif (strlen($phone) === 9) {
            return substr($phone, 0, 3) . ' ' . substr($phone, 3, 2) . ' ' . 
                   substr($phone, 5, 2) . ' ' . substr($phone, 7, 2);
        }
        
        return $phone;
    }
}