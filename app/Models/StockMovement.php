<?php

namespace App\Models;

use App\Traits\TenantScope; // ← AJOUTER
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use TenantScope; // ← AJOUTER

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'purchase_price',
        'sale_price',
        'stock_after',
        'motif',
        'reference_document',
        'user_id',
        // 'owner_id' n'est PAS dans fillable (sera auto-assigné)
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'quantity' => 'integer',
        'stock_after' => 'integer',
        'purchase_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];
    
    // ============ RELATIONS ============

    /**
     * Le produit concerné par le mouvement
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * L'utilisateur qui a effectué le mouvement
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 👇 Propriétaire (super_admin de la quincaillerie)
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // ============ ACCESSORS ============

    /**
     * Type de mouvement en français
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'entree' ? 'Entrée' : 'Sortie';
    }

    /**
     * Classe CSS pour le type de mouvement
     */
    public function getTypeClassAttribute(): string
    {
        return $this->type === 'entree' ? 'text-green-600 bg-green-100' : 'text-red-600 bg-red-100';
    }

    /**
     * Icône pour le type de mouvement
     */
    public function getTypeIconAttribute(): string
    {
        return $this->type === 'entree' ? 'bi-arrow-down-circle' : 'bi-arrow-up-circle';
    }

    /**
     * Quantité formatée avec signe
     */
    public function getFormattedQuantityAttribute(): string
    {
        $sign = $this->type === 'entree' ? '+' : '-';
        return $sign . ' ' . $this->quantity;
    }

    /**
     * Quantité formatée pour affichage
     */
    public function getDisplayQuantityAttribute(): string
    {
        return $this->quantity . ' unité(s)';
    }

    /**
     * Stock après formaté
     */
    public function getFormattedStockAfterAttribute(): string
    {
        return $this->stock_after . ' unité(s)';
    }

    /**
     * Date formatée
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Prix d'achat formaté
     */
    public function getFormattedPurchasePriceAttribute(): ?string
    {
        return $this->purchase_price ? number_format($this->purchase_price, 0, ',', ' ') . ' FCFA' : null;
    }

    /**
     * Prix de vente formaté
     */
    public function getFormattedSalePriceAttribute(): ?string
    {
        return $this->sale_price ? number_format($this->sale_price, 0, ',', ' ') . ' FCFA' : null;
    }

    /**
     * Référence courte
     */
    public function getShortReferenceAttribute(): string
    {
        if (!$this->reference_document) return 'N/A';
        
        $parts = explode('-', $this->reference_document);
        return end($parts);
    }

    /**
     * Vérifier si c'est une entrée
     */
    public function getIsEntryAttribute(): bool
    {
        return $this->type === 'entree';
    }

    /**
     * Vérifier si c'est une sortie
     */
    public function getIsExitAttribute(): bool
    {
        return $this->type === 'sortie';
    }

    // ============ SCOPES ============

    /**
     * 👇 Filtrer les mouvements de la même quincaillerie
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
     * Uniquement les entrées
     */
    public function scopeEntries($query)
    {
        return $query->where('type', 'entree');
    }

    /**
     * Uniquement les sorties
     */
    public function scopeExits($query)
    {
        return $query->where('type', 'sortie');
    }

    /**
     * Mouvements d'un produit spécifique
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Mouvements d'aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Mouvements de cette semaine
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Mouvements de ce mois
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    /**
     * Mouvements entre deux dates
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Mouvements avec quantité minimum
     */
    public function scopeMinQuantity($query, $quantity)
    {
        return $query->where('quantity', '>=', $quantity);
    }

    /**
     * Mouvements avec un motif spécifique
     */
    public function scopeWithMotif($query, $motif)
    {
        return $query->where('motif', 'LIKE', '%' . $motif . '%');
    }

    /**
     * Mouvements avec une référence
     */
    public function scopeWithReference($query)
    {
        return $query->whereNotNull('reference_document');
    }

    // ============ MÉTHODES ============

    /**
     * Créer une entrée de stock
     */
    public static function createEntry(Product $product, $quantity, $motif, $reference = null, $purchasePrice = null, $salePrice = null)
    {
        $oldStock = $product->stock;
        $newStock = $oldStock + $quantity;
        
        $movement = self::create([
            'product_id' => $product->id,
            'type' => 'entree',
            'quantity' => $quantity,
            'purchase_price' => $purchasePrice ?? $product->purchase_price,
            'sale_price' => $salePrice ?? $product->sale_price,
            'stock_after' => $newStock,
            'motif' => $motif,
            'reference_document' => $reference,
            'user_id' => auth()->id(),
        ]);

        // Mettre à jour le stock du produit
        $product->update(['stock' => $newStock]);

        return $movement;
    }

    /**
     * Créer une sortie de stock
     */
    public static function createExit(Product $product, $quantity, $motif, $reference = null)
    {
        if ($product->stock < $quantity) {
            throw new \Exception("Stock insuffisant. Disponible: {$product->stock}, Demandé: {$quantity}");
        }

        $oldStock = $product->stock;
        $newStock = $oldStock - $quantity;
        
        $movement = self::create([
            'product_id' => $product->id,
            'type' => 'sortie',
            'quantity' => $quantity,
            'purchase_price' => $product->purchase_price,
            'sale_price' => $product->sale_price,
            'stock_after' => $newStock,
            'motif' => $motif,
            'reference_document' => $reference,
            'user_id' => auth()->id(),
        ]);

        // Mettre à jour le stock du produit
        $product->update(['stock' => $newStock]);

        return $movement;
    }

    /**
     * Annuler un mouvement de stock
     */
    public function cancel()
    {
        $product = $this->product;
        
        if (!$product) {
            throw new \Exception("Produit non trouvé");
        }

        // Mouvement inverse
        if ($this->type === 'entree') {
            // Si c'était une entrée, on doit sortir la quantité
            if ($product->stock < $this->quantity) {
                throw new \Exception("Impossible d'annuler : stock insuffisant");
            }
            $product->decrement('stock', $this->quantity);
        } else {
            // Si c'était une sortie, on doit rentrer la quantité
            $product->increment('stock', $this->quantity);
        }

        // Créer un mouvement d'annulation
        $cancelMovement = self::create([
            'product_id' => $this->product_id,
            'type' => $this->type === 'entree' ? 'sortie' : 'entree',
            'quantity' => $this->quantity,
            'purchase_price' => $this->purchase_price,
            'sale_price' => $this->sale_price,
            'stock_after' => $product->fresh()->stock,
            'motif' => 'ANNULATION: ' . $this->motif,
            'reference_document' => 'CANCEL-' . $this->id,
            'user_id' => auth()->id(),
        ]);

        return $cancelMovement;
    }

    /**
     * Récupérer les détails du mouvement
     */
    public function getDetails(): array
    {
        return [
            'id' => $this->id,
            'product' => $this->product?->name ?? 'Produit inconnu',
            'product_id' => $this->product_id,
            'type' => $this->type_label,
            'type_class' => $this->type_class,
            'type_icon' => $this->type_icon,
            'quantity' => $this->quantity,
            'formatted_quantity' => $this->formatted_quantity,
            'display_quantity' => $this->display_quantity,
            'stock_before' => $this->stock_after - $this->quantity,
            'stock_after' => $this->stock_after,
            'formatted_stock_after' => $this->formatted_stock_after,
            'motif' => $this->motif,
            'reference' => $this->reference_document,
            'short_reference' => $this->short_reference,
            'purchase_price' => $this->formatted_purchase_price,
            'sale_price' => $this->formatted_sale_price,
            'user' => $this->user?->name ?? 'Système',
            'date' => $this->formatted_date,
            'is_entry' => $this->is_entry,
            'is_exit' => $this->is_exit,
        ];
    }

    /**
     * Vérifier si le mouvement peut être annulé
     */
    public function isCancellable(): bool
    {
        // On ne peut annuler que les mouvements récents (moins de 24h)
        return $this->created_at->diffInHours(now()) < 24;
    }

    /**
     * Calculer la valeur du mouvement
     */
    public function getValueAttribute(): float
    {
        if ($this->type === 'entree') {
            return $this->quantity * ($this->purchase_price ?? 0);
        } else {
            return $this->quantity * ($this->sale_price ?? 0);
        }
    }

    /**
     * Valeur formatée
     */
    public function getFormattedValueAttribute(): string
    {
        return number_format($this->value, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Récupérer le prix utilisé (achat pour entrée, vente pour sortie)
     */
    public function getRelevantPriceAttribute(): ?float
    {
        return $this->type === 'entree' ? $this->purchase_price : $this->sale_price;
    }

    /**
     * Prix pertinent formaté
     */
    public function getFormattedRelevantPriceAttribute(): ?string
    {
        $price = $this->relevant_price;
        return $price ? number_format($price, 0, ',', ' ') . ' FCFA' : null;
    }

    /**
     * Scope pour les statistiques
     */
    public static function getStats($productId = null, $startDate = null, $endDate = null)
    {
        $query = self::query();
        
        if ($productId) {
            $query->forProduct($productId);
        }
        
        if ($startDate && $endDate) {
            $query->betweenDates($startDate, $endDate);
        }
        
        $entries = (clone $query)->entries()->sum('quantity');
        $exits = (clone $query)->exits()->sum('quantity');
        
        return [
            'total_entries' => $entries,
            'total_exits' => $exits,
            'net_flow' => $entries - $exits,
            'total_movements' => $query->count(),
        ];
    }
}