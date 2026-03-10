<?php

namespace App\Models;

use App\Traits\TenantScope; // ← AJOUTER
use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    use TenantScope; // ← AJOUTER

    protected $fillable = [
        'sale_id',
        'product_id',
        'quantity',
        'unit_price',
        'total_price',
        // 'owner_id' n'est PAS dans fillable (sera auto-assigné)
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
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
     * 👇 Propriétaire (super_admin de la quincaillerie)
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // ============ ACCESSORS ============

    /**
     * Prix unitaire formaté
     */
    public function getFormattedUnitPriceAttribute()
    {
        return number_format($this->unit_price, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Prix total formaté
     */
    public function getFormattedTotalPriceAttribute()
    {
        return number_format($this->total_price, 0, ',', ' ') . ' FCFA';
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
        return 'VENTE #' . $this->sale_id;
    }

    /**
     * Bénéfice sur cet article
     */
    public function getProfitAttribute()
    {
        if (!$this->product) return 0;
        
        $purchasePrice = $this->product->purchase_price ?? 0;
        return ($this->unit_price - $purchasePrice) * $this->quantity;
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
        if (!$this->product || $this->product->purchase_price == 0) return 0;
        
        return round((($this->unit_price - $this->product->purchase_price) / $this->product->purchase_price) * 100, 1);
    }

    // ============ SCOPES ============

    /**
     * 👇 Filtrer les articles de la même quincaillerie
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
    public static function createFromProduct(Product $product, $quantity, $saleId, $unitPrice = null)
    {
        $unitPrice = $unitPrice ?? $product->sale_price;
        $totalPrice = self::calculateTotalPrice($quantity, $unitPrice);
        
        return self::create([
            'sale_id' => $saleId,
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $totalPrice,
        ]);
    }

    /**
     * Mettre à jour la quantité
     */
    public function updateQuantity($newQuantity)
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
    public function updateUnitPrice($newUnitPrice)
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
    public function getDetails()
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
            'profit' => $this->profit,
            'formatted_profit' => $this->formatted_profit,
            'profit_margin' => $this->profit_margin,
            'date' => $this->created_at->format('d/m/Y H:i'),
        ];
    }

    /**
     * Vérifier si l'article peut être modifié
     */
    public function isEditable()
    {
        // On ne peut modifier que les articles des ventes du jour
        return $this->sale && $this->sale->created_at->isToday();
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
        $calculatedTotal = $this->quantity * $this->unit_price;
        if (abs($calculatedTotal - $this->total_price) > 0.01) {
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
}