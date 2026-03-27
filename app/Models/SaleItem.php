<?php

namespace App\Models;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SaleItem extends Model
{
    use TenantScope;

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'price',
        'purchase_price',
        'discount',
        'total',
        'owner_id',
        'tenant_id',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    // ============ RELATIONS ============

    /**
     * La vente parente
     */
    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * Le produit vendu
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
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
     * Prix unitaire formaté
     */
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Prix total formaté
     */
    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Nom du produit
     */
    public function getProductNameAttribute()
    {
        return $this->product?->name ?? 'Produit inconnu';
    }

    /**
     * Référence de la vente
     */
    public function getSaleReferenceAttribute()
    {
        return $this->sale?->invoice_number ?? 'VENTE #' . $this->sale_id;
    }

    /**
     * Bénéfice sur cet article
     */
    public function getProfitAttribute()
    {
        if (!$this->product) return 0;
        
        $purchasePrice = $this->purchase_price ?? $this->product->purchase_price ?? 0;
        return ($this->price - $purchasePrice) * $this->quantity;
    }

    /**
     * Bénéfice formaté
     */
    public function getFormattedProfitAttribute()
    {
        return number_format($this->profit, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Marge bénéficiaire en pourcentage
     */
    public function getProfitMarginAttribute()
    {
        $purchasePrice = $this->purchase_price ?? $this->product?->purchase_price ?? 0;
        if ($purchasePrice == 0) return 0;
        
        return round((($this->price - $purchasePrice) / $purchasePrice) * 100, 1);
    }

    // ============ SCOPES ============

    /**
     * Filtrer les articles de la même quincaillerie - Version compatible
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
        return $query->where('price', '>=', $price);
    }

    /**
     * Articles avec un prix unitaire maximum
     */
    public function scopeMaxUnitPrice($query, $price)
    {
        return $query->where('price', '<=', $price);
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

    // ============ MÉTHODES ============

    /**
     * Calculer le prix total
     */
    public static function calculateTotalPrice($quantity, $unitPrice)
    {
        return $quantity * $unitPrice;
    }

    /**
     * Créer un article de vente
     */
    public static function createFromProduct(Product $product, $quantity, $saleId, $unitPrice = null, $tenantId = null, $ownerId = null)
    {
        $unitPrice = $unitPrice ?? $product->sale_price;
        $totalPrice = self::calculateTotalPrice($quantity, $unitPrice);
        
        return self::create([
            'sale_id' => $saleId,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'price' => $unitPrice,
            'purchase_price' => $product->purchase_price,
            'total' => $totalPrice,
            'tenant_id' => $tenantId ?? $product->tenant_id,
            'owner_id' => $ownerId ?? $product->owner_id,
        ]);
    }

    /**
     * Mettre à jour la quantité
     */
    public function updateQuantity($newQuantity)
    {
        $this->quantity = $newQuantity;
        $this->total = $this->calculateTotalPrice($newQuantity, $this->price);
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
    public function updateUnitPrice($newUnitPrice)
    {
        $this->price = $newUnitPrice;
        $this->total = $this->calculateTotalPrice($this->quantity, $newUnitPrice);
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
    public function getDetails()
    {
        return [
            'id' => $this->id,
            'sale_id' => $this->sale_id,
            'sale_reference' => $this->sale_reference,
            'product_id' => $this->product_id,
            'product_name' => $this->product_name,
            'quantity' => $this->quantity,
            'unit_price' => $this->price,
            'formatted_unit_price' => $this->formatted_unit_price,
            'total_price' => $this->total,
            'formatted_total_price' => $this->formatted_total_price,
            'purchase_price' => $this->purchase_price,
            'profit' => $this->profit,
            'formatted_profit' => $this->formatted_profit,
            'profit_margin' => $this->profit_margin,
            'date' => $this->created_at?->format('d/m/Y H:i'),
        ];
    }

    /**
     * Vérifier si l'article peut être modifié
     */
    public function isEditable()
    {
        // On ne peut modifier que les articles des ventes du jour et non annulées
        return $this->sale && 
               $this->sale->created_at->isToday() && 
               $this->sale->status !== 'cancelled';
    }

    /**
     * Récupérer le stock avant vente (si disponible)
     */
    public function getStockBeforeSale()
    {
        if (!$this->product) return null;
        
        // Le stock avant vente = stock actuel + quantité vendue
        return ($this->product->stock ?? 0) + $this->quantity;
    }

    /**
     * Vérifier la cohérence des données
     */
    public function checkConsistency()
    {
        $issues = [];
        
        // Vérifier que le prix total correspond
        $calculatedTotal = $this->quantity * $this->price;
        if (abs($calculatedTotal - $this->total) > 0.01) {
            $issues[] = 'Le prix total ne correspond pas à quantité × prix unitaire';
        }
        
        // Vérifier que le produit existe
        if (!$this->product) {
            $issues[] = 'Le produit associé n\'existe pas';
        }
        
        // Vérifier que la vente existe
        if (!$this->sale) {
            $issues[] = 'La vente associée n\'existe pas';
        }
        
        return [
            'is_consistent' => empty($issues),
            'issues' => $issues,
        ];
    }

    /**
     * Récupérer les statistiques des ventes par produit
     * Version optimisée pour PostgreSQL
     */
    public static function getSalesStatsByProduct($tenantId = null, $startDate = null, $endDate = null)
    {
        $query = self::select(
                'product_id',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('AVG(price) as average_price'),
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
                DB::raw('SUM(sale_items.total) as total_revenue'),
                DB::raw('COUNT(DISTINCT sales.id) as total_sales')
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
}