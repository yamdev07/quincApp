<?php

namespace App\Models;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleItem extends Model
{
    use TenantScope;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',      // Utiliser unit_price au lieu de price
        'total_price',     // Utiliser total_price au lieu de total
        'tenant_id',
        'owner_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ============ RELATIONS ============

    /**
     * La vente parente
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Le produit vendu
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Propriétaire (super_admin de la quincaillerie)
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Tenant (quincaillerie)
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    // ============ ACCESSORS ============

    /**
     * Prix unitaire formaté
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format($this->unit_price, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Prix total formaté
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return number_format($this->total_price, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Nom du produit
     */
    public function getProductNameAttribute(): string
    {
        return $this->product?->name ?? 'Produit inconnu';
    }

    /**
     * Référence de la vente
     */
    public function getSaleReferenceAttribute(): string
    {
        return $this->sale?->invoice_number ?? 'VENTE #' . $this->sale_id;
    }

    /**
     * Bénéfice sur cet article (calculé)
     */
    public function getProfitAttribute(): float|int
    {
        if (!$this->product) return 0;
        
        $purchasePrice = $this->product->purchase_price ?? 0;
        return ($this->unit_price - $purchasePrice) * $this->quantity;
    }

    /**
     * Bénéfice formaté
     */
    public function getFormattedProfitAttribute(): string
    {
        return number_format($this->profit, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Marge bénéficiaire en pourcentage
     */
    public function getProfitMarginAttribute(): float|int
    {
        $purchasePrice = $this->product?->purchase_price ?? 0;
        if ($purchasePrice == 0) return 0;
        
        return round((($this->unit_price - $purchasePrice) / $purchasePrice) * 100, 1);
    }

    /**
     * Sous-total (quantity * unit_price)
     */
    public function getSubtotalAttribute(): float|int
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Date formatée
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at?->format('d/m/Y H:i') ?? 'N/A';
    }

    /**
     * Prix d'achat (via le produit)
     */
    public function getPurchasePriceAttribute(): float|int
    {
        return $this->product?->purchase_price ?? 0;
    }

    // ============ SCOPES ============

    /**
     * Filtrer les articles de la même quincaillerie
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
     * Articles d'une vente spécifique
     */
    public function scopeBySale($query, $saleId)
    {
        return $query->where('sale_id', $saleId);
    }

    /**
     * Articles d'un produit spécifique
     */
    public function scopeByProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Articles avec un prix unitaire minimum
     */
    public function scopeMinUnitPrice($query, $price)
    {
        return $query->where('unit_price', '>=', $price);
    }

    /**
     * Articles avec un prix unitaire maximum
     */
    public function scopeMaxUnitPrice($query, $price)
    {
        return $query->where('unit_price', '<=', $price);
    }

    /**
     * Articles avec une quantité minimum
     */
    public function scopeMinQuantity($query, $qty)
    {
        return $query->where('quantity', '>=', $qty);
    }

    /**
     * Articles d'une période spécifique (via la vente)
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereHas('sale', function($q) use ($startDate, $endDate) {
            $q->whereBetween('created_at', [$startDate, $endDate]);
        });
    }

    /**
     * Articles du jour
     */
    public function scopeToday($query)
    {
        return $query->whereHas('sale', function($q) {
            $q->whereDate('created_at', today());
        });
    }

    /**
     * Articles de la semaine
     */
    public function scopeThisWeek($query)
    {
        return $query->whereHas('sale', function($q) {
            $q->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        });
    }

    /**
     * Articles du mois
     */
    public function scopeThisMonth($query)
    {
        return $query->whereHas('sale', function($q) {
            $q->whereMonth('created_at', now()->month)
              ->whereYear('created_at', now()->year);
        });
    }

    // ============ MÉTHODES STATIQUES ============

    /**
     * Calculer le prix total
     */
    public static function calculateTotalPrice($quantity, $unitPrice): float|int
    {
        return $quantity * $unitPrice;
    }

    /**
     * Créer un article de vente
     */
    public static function createFromProduct(Product $product, $quantity, $saleId, $unitPrice = null, $tenantId = null, $ownerId = null): self
    {
        $unitPrice = $unitPrice ?? $product->sale_price;
        $totalPrice = self::calculateTotalPrice($quantity, $unitPrice);
        
        $data = [
            'sale_id' => $saleId,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
            'tenant_id' => $tenantId ?? $product->tenant_id,
            'owner_id' => $ownerId ?? $product->owner_id,
        ];
        
        return self::create($data);
    }

    // ============ MÉTHODES D'INSTANCE ============

    /**
     * Mettre à jour la quantité
     */
    public function updateQuantity($newQuantity): self
    {
        $this->quantity = $newQuantity;
        $this->total_price = $this->calculateTotalPrice($newQuantity, $this->unit_price);
        $this->save();
        
        // Mettre à jour le total de la vente parente
        if ($this->sale) {
            $this->sale->updateTotal();
        }
        
        return $this;
    }

    /**
     * Mettre à jour le prix unitaire
     */
    public function updateUnitPrice($newUnitPrice): self
    {
        $this->unit_price = $newUnitPrice;
        $this->total_price = $this->calculateTotalPrice($this->quantity, $newUnitPrice);
        $this->save();
        
        // Mettre à jour le total de la vente parente
        if ($this->sale) {
            $this->sale->updateTotal();
        }
        
        return $this;
    }

    /**
     * Récupérer les informations détaillées
     */
    public function getDetails(): array
    {
        return [
            'id' => $this->id,
            'sale_id' => $this->sale_id,
            'sale_reference' => $this->sale_reference,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'quantity' => $this->quantity,
            'unit_price' => $this->unit_price,
            'formatted_unit_price' => $this->formatted_unit_price,
            'total_price' => $this->total_price,
            'formatted_total_price' => $this->formatted_total_price,
            'subtotal' => $this->subtotal,
            'purchase_price' => $this->purchase_price,
            'profit' => $this->profit,
            'formatted_profit' => $this->formatted_profit,
            'profit_margin' => $this->profit_margin,
            'date' => $this->formatted_date,
            'tenant_id' => $this->tenant_id,
            'owner_id' => $this->owner_id,
        ];
    }

    /**
     * Vérifier si l'article peut être modifié
     */
    public function isEditable(): bool
    {
        // On ne peut modifier que les articles des ventes du jour et non annulées
        return $this->sale && 
               $this->sale->created_at->isToday() && 
               $this->sale->status !== 'cancelled';
    }

    /**
     * Récupérer le stock avant vente (si disponible)
     */
    public function getStockBeforeSale(): ?int
    {
        if (!$this->product) return null;
        
        // Le stock avant vente = stock actuel + quantité vendue
        return ($this->product->stock ?? 0) + $this->quantity;
    }

    /**
     * Vérifier la cohérence des données
     */
    public function checkConsistency(): array
    {
        $issues = [];
        
        // Vérifier que le prix total correspond
        $calculatedTotal = $this->quantity * $this->unit_price;
        if (abs($calculatedTotal - $this->total_price) > 0.01) {
            $issues[] = 'Le prix total ne correspond pas à quantité × prix unitaire';
        }
        
        // Vérifier que la quantité est positive
        if ($this->quantity <= 0) {
            $issues[] = 'La quantité doit être positive';
        }
        
        // Vérifier que le prix unitaire est positif
        if ($this->unit_price <= 0) {
            $issues[] = 'Le prix unitaire doit être positif';
        }
        
        // Vérifier que le produit existe
        if (!$this->product) {
            $issues[] = 'Le produit associé n\'existe pas';
        }
        
        // Vérifier que la vente existe
        if (!$this->sale) {
            $issues[] = 'La vente associée n\'existe pas';
        }
        
        // Vérifier que le stock est suffisant (si non annulé)
        if ($this->sale && $this->sale->status !== 'cancelled' && $this->product) {
            $stockBefore = $this->getStockBeforeSale();
            if ($stockBefore < $this->quantity) {
                $issues[] = "Stock insuffisant avant vente: {$stockBefore} disponible, {$this->quantity} vendu";
            }
        }
        
        return [
            'is_consistent' => empty($issues),
            'issues' => $issues,
        ];
    }

    /**
     * Annuler l'article (restaurer le stock)
     */
    public function cancel(): bool
    {
        if ($this->sale && $this->sale->status === 'cancelled') {
            return false;
        }
        
        // Restaurer le stock du produit
        if ($this->product && $this->quantity > 0) {
            $this->product->stock += $this->quantity;
            $this->product->save();
        }
        
        return true;
    }

    // ============ MÉTHODES STATISTIQUES ============

    /**
     * Récupérer les statistiques des ventes par produit
     */
    public static function getSalesStatsByProduct($tenantId = null, $startDate = null, $endDate = null)
    {
        $query = self::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('AVG(unit_price) as average_price'),
                DB::raw('COUNT(DISTINCT sale_id) as number_of_sales')
            )
            ->with('product')
            ->groupBy('product_id');
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        if ($startDate && $endDate) {
            $query->whereHas('sale', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }
        
        return $query->orderBy('total_quantity', 'desc')->get();
    }

    /**
     * Récupérer le total des ventes par jour
     */
    public static function getDailySalesStats($tenantId = null, $days = 30)
    {
        $query = self::join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->select(
                DB::raw('DATE(sales.created_at) as date'),
                DB::raw('SUM(sale_items.quantity) as total_quantity'),
                DB::raw('SUM(sale_items.total_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT sales.id) as total_sales'),
                DB::raw('AVG(sale_items.unit_price) as average_price')
            )
            ->where('sales.status', 'completed')
            ->where('sales.created_at', '>=', now()->subDays($days))
            ->groupBy(DB::raw('DATE(sales.created_at)'))
            ->orderBy('date', 'desc');
        
        if ($tenantId) {
            $query->where('sale_items.tenant_id', $tenantId);
        }
        
        return $query->get();
    }

    /**
     * Récupérer les meilleures ventes (top produits)
     */
    public static function getTopProducts($limit = 10, $tenantId = null, $startDate = null, $endDate = null)
    {
        $query = self::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total_price) as total_revenue'),
                DB::raw('COUNT(DISTINCT sale_id) as number_of_sales')
            )
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_quantity', 'desc')
            ->limit($limit);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        if ($startDate && $endDate) {
            $query->whereHas('sale', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }
        
        return $query->get();
    }

    /**
     * Récupérer les produits les plus rentables
     */
    public static function getMostProfitableProducts($limit = 10, $tenantId = null, $startDate = null, $endDate = null)
    {
        $query = self::select(
                'product_id',
                DB::raw('SUM((unit_price - products.purchase_price) * quantity) as total_profit'),
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('AVG((unit_price - products.purchase_price) / products.purchase_price * 100) as avg_margin')
            )
            ->join('products', 'sale_items.product_id', '=', 'products.id')
            ->with('product')
            ->groupBy('product_id')
            ->orderBy('total_profit', 'desc')
            ->limit($limit);
        
        if ($tenantId) {
            $query->where('sale_items.tenant_id', $tenantId);
        }
        
        if ($startDate && $endDate) {
            $query->whereHas('sale', function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [$startDate, $endDate]);
            });
        }
        
        return $query->get();
    }
}