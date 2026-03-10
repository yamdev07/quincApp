<?php

namespace App\Models;

use App\Traits\TenantScope; // ← AJOUTER
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory, TenantScope; // ← AJOUTER TenantScope

    protected $fillable = [
        'client_id',
        'user_id',
        'total_price',
        // 'owner_id' n'est PAS dans fillable (sera auto-assigné)
    ];

    protected $casts = [
        'total_price' => 'decimal:2',
    ];

    // ============ RELATIONS ============

    /**
     * Tous les articles de cette vente
     */
    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Client associé à cette vente
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Utilisateur (caissier) qui a enregistré la vente
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
     * Nombre d'articles dans la vente
     */
    public function getItemCountAttribute()
    {
        return $this->items()->count();
    }

    /**
     * Quantité totale d'articles vendus
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items()->sum('quantity');
    }

    /**
     * Statut de la vente (payée, en attente, annulée)
     * À ajouter dans la migration si nécessaire
     */
    public function getStatusLabelAttribute()
    {
        $statuses = [
            'completed' => 'Terminée',
            'pending' => 'En attente',
            'cancelled' => 'Annulée',
        ];
        
        return $statuses[$this->status ?? 'completed'] ?? 'Terminée';
    }

    /**
     * Montant total formaté
     */
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_price, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Date formatée
     */
    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d/m/Y H:i');
    }

    /**
     * Récupérer le caissier
     */
    public function getCashierNameAttribute()
    {
        return $this->user?->name ?? 'Inconnu';
    }

    // ============ SCOPES ============

    /**
     * 👇 Filtrer les ventes de la même quincaillerie
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
     * Ventes d'aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Ventes de cette semaine
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Ventes de ce mois
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                     ->whereYear('created_at', now()->year);
    }

    /**
     * Ventes d'un client spécifique
     */
    public function scopeByClient($query, $clientId)
    {
        return $query->where('client_id', $clientId);
    }

    /**
     * Ventes d'un caissier spécifique
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Ventes entre deux dates
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Ventes d'un montant minimum
     */
    public function scopeMinAmount($query, $amount)
    {
        return $query->where('total_price', '>=', $amount);
    }

    /**
     * Ventes d'un montant maximum
     */
    public function scopeMaxAmount($query, $amount)
    {
        return $query->where('total_price', '<=', $amount);
    }

    // ============ MÉTHODES ============

    /**
     * Ajouter un article à la vente
     */
    public function addItem(Product $product, $quantity, $unitPrice = null)
    {
        $unitPrice = $unitPrice ?? $product->sale_price;
        
        $item = $this->items()->create([
            'product_id' => $product->id,
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $unitPrice * $quantity,
        ]);

        // Mettre à jour le total de la vente
        $this->updateTotal();

        return $item;
    }

    /**
     * Mettre à jour le montant total de la vente
     */
    public function updateTotal()
    {
        $total = $this->items()->sum('total_price');
        $this->update(['total_price' => $total]);
        
        return $this;
    }

    /**
     * Annuler la vente (restaurer le stock)
     */
    public function cancel()
    {
        // Restaurer le stock pour chaque article
        foreach ($this->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->increment('stock', $item->quantity);
                
                // Enregistrer le mouvement de stock
                StockMovement::create([
                    'product_id' => $product->id,
                    'type' => 'entree',
                    'quantity' => $item->quantity,
                    'purchase_price' => $product->purchase_price,
                    'sale_price' => $product->sale_price,
                    'stock_after' => $product->stock + $item->quantity,
                    'motif' => 'Annulation vente #' . $this->id,
                    'reference_document' => 'CANCEL-' . $this->id,
                    'user_id' => auth()->id(),
                    'owner_id' => $this->owner_id,
                ]);
            }
        }

        // Marquer la vente comme annulée (si vous avez un champ status)
        if (in_array('status', $this->getFillable())) {
            $this->update(['status' => 'cancelled']);
        }

        return $this;
    }

    /**
     * Récupérer les statistiques détaillées de la vente
     */
    public function getDetailedStats()
    {
        $items = $this->items()->with('product')->get();
        
        return [
            'id' => $this->id,
            'date' => $this->formatted_date,
            'client' => $this->client?->name ?? 'Client non spécifié',
            'cashier' => $this->cashier_name,
            'total' => $this->formatted_total,
            'item_count' => $this->item_count,
            'total_quantity' => $this->total_quantity,
            'items' => $items->map(function($item) {
                return [
                    'product' => $item->product?->name ?? 'Produit inconnu',
                    'quantity' => $item->quantity,
                    'unit_price' => number_format($item->unit_price, 0, ',', ' ') . ' FCFA',
                    'total' => number_format($item->total_price, 0, ',', ' ') . ' FCFA',
                ];
            }),
        ];
    }

    /**
     * Générer la facture PDF (placeholder)
     */
    public function generateInvoice()
    {
        // À implémenter avec une bibliothèque PDF
        return null;
    }

    /**
     * Vérifier si la vente peut être modifiée
     */
    public function isEditable()
    {
        // Par exemple, on ne peut modifier que les ventes du jour
        return $this->created_at->isToday();
    }

    /**
     * Dupliquer la vente (pour retour client par exemple)
     */
    public function duplicate()
    {
        $newSale = $this->replicate();
        $newSale->created_at = now();
        $newSale->save();

        foreach ($this->items as $item) {
            $newItem = $item->replicate();
            $newItem->sale_id = $newSale->id;
            $newItem->save();
        }

        return $newSale;
    }

    /**
     * Récupérer les produits vendus dans cette vente
     */
    public function products()
    {
        return Product::whereIn('id', $this->items()->pluck('product_id'))->get();
    }
}