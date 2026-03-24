<?php

namespace App\Models;

use App\Traits\TenantScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory, TenantScope;

    // Les champs qui peuvent être assignés en masse
    protected $fillable = [
        'name',
        'description',
        'sale_price',
        'discount',
        'purchase_price',
        'quantity',
        'stock',
        'supplier_id',
        'category_id',
        'owner_id',
        'tenant_id',
        'user_id',
    ];

    // Conversion des types de données
    protected $casts = [
        'sale_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'quantity' => 'integer',
        'stock' => 'integer',
    ];

    // ============ RELATIONS ============
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class)->orderBy('created_at', 'desc');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ============ ACCESSORS ============
    
    public function getSoldQuantityAttribute()
    {
        return $this->quantity - $this->stock;
    }

    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return 'out_of_stock';
        } elseif ($this->stock <= 5) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }

    public function getStockValuePurchaseAttribute()
    {
        return $this->stock * $this->purchase_price;
    }

    public function getStockValueSaleAttribute()
    {
        return $this->stock * $this->sale_price;
    }

    public function getPotentialProfitAttribute()
    {
        return ($this->sale_price - $this->purchase_price) * $this->stock;
    }

    public function getProfitMarginAttribute()
    {
        if ($this->purchase_price == 0) return 0;
        return (($this->sale_price - $this->purchase_price) / $this->purchase_price) * 100;
    }

    // ============ SCOPES ============
    
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
    
    public function scopeOutOfStock($query)
    {
        return $query->where('stock', '=', 0);
    }
    
    public function scopeLowStock($query, $threshold = 5)
    {
        return $query->where('stock', '<=', $threshold)->where('stock', '>', 0);
    }
    
    public function scopeBySupplier($query, $supplier_id)
    {
        return $query->where('supplier_id', $supplier_id);
    }
    
    public function scopeByCategory($query, $category_id)
    {
        return $query->where('category_id', $category_id);
    }
    
    /**
     * Scope pour les produits avec stocks multiples (achats différents)
     * Version compatible PostgreSQL
     */
    public function scopeWithMultipleBatches($query)
    {
        return $query->whereExists(function($q) {
            $q->select(DB::raw(1))
                ->from('stock_movements')
                ->whereColumn('stock_movements.product_id', 'products.id')
                ->where('stock_movements.type', 'entree')
                ->whereColumn('stock_movements.tenant_id', 'products.tenant_id')
                ->groupBy('stock_movements.product_id')
                ->havingRaw('COUNT(DISTINCT stock_movements.purchase_price) > 1');
        });
    }

    public function scopeByOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    public function scopeByTenant($query, $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    // ============ MÉTHODES DE GESTION DE STOCK ============
    
    public function canSell($quantity)
    {
        return $this->stock >= $quantity;
    }
    
    public function sell($quantity)
    {
        if (!$this->canSell($quantity)) {
            throw new \Exception("Stock insuffisant. Disponible: {$this->stock}, Demandé: {$quantity}");
        }
        
        $this->decrement('stock', $quantity);
        return $this;
    }
    
    public function restock($quantity, $purchase_price = null)
    {
        $this->increment('stock', $quantity);
        $this->increment('quantity', $quantity);
        
        if ($purchase_price) {
            $this->update(['purchase_price' => $purchase_price]);
        }
        
        return $this;
    }
    
    public function adjustStock($newStock, $reason = null)
    {
        if ($newStock > $this->stock) {
            $difference = $newStock - $this->stock;
            $this->increment('quantity', $difference);
        }
        
        $this->update(['stock' => $newStock]);
        
        return $this;
    }
    
    public function syncQuantity()
    {
        $sold = $this->saleItems()->sum('quantity') ?? 0;
        $this->update(['quantity' => $this->stock + $sold]);
        
        return $this;
    }

    // ============ MÉTHODES POUR STOCKS GROUPÉS ============
    
    /**
     * Récupère les stocks groupés par lot/prix d'achat
     */
    public function getGroupedStocks()
    {
        return StockMovement::where('product_id', $this->id)
            ->where('type', 'entree')
            ->selectRaw('
                product_id,
                reference_document as batch,
                purchase_price,
                sale_price,
                SUM(quantity) as total_quantity,
                MAX(created_at) as last_updated
            ')
            ->groupBy('product_id', 'reference_document', 'purchase_price', 'sale_price')
            ->orderBy('last_updated', 'desc')
            ->get();
    }

    /**
     * Calcule les totaux pour ce produit (regroupés)
     */
    public function getStockTotals()
    {
        $groupedStocks = $this->getGroupedStocks();
        
        $totalQuantity = $groupedStocks->sum('total_quantity');
        $averagePurchasePrice = $groupedStocks->avg('purchase_price');
        
        return [
            'grouped_stocks' => $groupedStocks,
            'total_quantity_all_batches' => $totalQuantity,
            'average_purchase_price' => $averagePurchasePrice,
            'current_sale_price' => $this->sale_price,
            'total_value_current' => $groupedStocks->sum(function($stock) {
                return $stock->total_quantity * ($this->sale_price);
            }),
            'total_value_purchase' => $groupedStocks->sum(function($stock) {
                return $stock->total_quantity * $stock->purchase_price;
            }),
            'number_of_batches' => $groupedStocks->count(),
            'profit_potential' => $totalQuantity * ($this->sale_price - $averagePurchasePrice),
        ];
    }

    /**
     * Récupère le résumé des stocks (pour affichage rapide)
     */
    public function getStockSummary()
    {
        $totals = $this->getStockTotals();
        
        return [
            'name' => $this->name,
            'total_stock' => $totals['total_quantity_all_batches'],
            'current_price' => $this->sale_price,
            'average_purchase_price' => $totals['average_purchase_price'],
            'total_value' => $totals['total_value_current'],
            'batches_count' => $totals['number_of_batches'],
            'has_multiple_batches' => $totals['number_of_batches'] > 1,
        ];
    }

    /**
     * Vérifie si le produit a des stocks groupés (achats multiples)
     * Version compatible PostgreSQL
     */
    public function hasMultipleBatches()
    {
        return StockMovement::where('product_id', $this->id)
            ->where('type', 'entree')
            ->select('purchase_price', 'reference_document')
            ->distinct()
            ->count() > 1;
    }

    /**
     * Récupère le prix d'achat moyen pondéré par la quantité
     */
    public function getWeightedAveragePurchasePrice()
    {
        $stocks = StockMovement::where('product_id', $this->id)
            ->where('type', 'entree')
            ->select('purchase_price', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('purchase_price')
            ->get();
        
        if ($stocks->isEmpty()) {
            return $this->purchase_price;
        }
        
        $totalValue = 0;
        $totalQuantity = 0;
        
        foreach ($stocks as $stock) {
            $totalValue += $stock->purchase_price * $stock->total_quantity;
            $totalQuantity += $stock->total_quantity;
        }
        
        return $totalQuantity > 0 ? $totalValue / $totalQuantity : 0;
    }

    /**
     * Calcule la valeur totale du stock par prix d'achat
     */
    public function getStockValueByPurchasePrice()
    {
        return StockMovement::where('product_id', $this->id)
            ->where('type', 'entree')
            ->select(
                'purchase_price',
                DB::raw('SUM(quantity) as total_quantity'),
                DB::raw('SUM(quantity * purchase_price) as total_value'),
                DB::raw('MAX(created_at) as last_update')
            )
            ->groupBy('purchase_price')
            ->orderBy('purchase_price', 'desc')
            ->get()
            ->map(function($item) {
                $item->current_value = $item->total_quantity * $this->sale_price;
                $item->potential_profit = $item->current_value - $item->total_value;
                return $item;
            });
    }

    /**
     * Obtient le lot avec le plus de stock
     */
    public function getLargestBatch()
    {
        return StockMovement::where('product_id', $this->id)
            ->where('type', 'entree')
            ->select('reference_document', 'purchase_price', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('reference_document', 'purchase_price')
            ->orderBy('total_quantity', 'desc')
            ->first();
    }

    /**
     * Obtient le lot le plus récent
     */
    public function getLatestBatch()
    {
        return StockMovement::where('product_id', $this->id)
            ->where('type', 'entree')
            ->select('reference_document', 'purchase_price', 'quantity', 'created_at')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Met à jour le prix de vente de tous les lots futurs
     */
    public function updateSalePriceForNewBatches($newSalePrice)
    {
        $this->update(['sale_price' => $newSalePrice]);
        
        return $this;
    }

    /**
     * Génère un rapport détaillé des stocks
     */
    public function generateStockReport()
    {
        $groupedStocks = $this->getGroupedStocks();
        $stockByPrice = $this->getStockValueByPurchasePrice();
        $largestBatch = $this->getLargestBatch();
        $latestBatch = $this->getLatestBatch();
        $weightedAvg = $this->getWeightedAveragePurchasePrice();
        
        return [
            'product_info' => [
                'id' => $this->id,
                'name' => $this->name,
                'current_stock' => $this->stock,
                'current_sale_price' => $this->sale_price,
                'profit_margin' => $this->profit_margin,
            ],
            'stock_analysis' => [
                'total_batches' => $groupedStocks->count(),
                'total_quantity' => $groupedStocks->sum('total_quantity'),
                'weighted_average_purchase' => $weightedAvg,
                'total_value_purchase' => $stockByPrice->sum('total_value'),
                'total_value_current' => $groupedStocks->sum(function($stock) {
                    return $stock->total_quantity * $this->sale_price;
                }),
                'total_potential_profit' => $groupedStocks->sum(function($stock) {
                    return $stock->total_quantity * ($this->sale_price - $stock->purchase_price);
                }),
            ],
            'largest_batch' => $largestBatch,
            'latest_batch' => $latestBatch,
            'stock_by_price' => $stockByPrice,
            'grouped_details' => $groupedStocks,
            'generated_at' => now()->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Vérifie la cohérence des stocks (quantité vs mouvements)
     */
    public function checkStockConsistency()
    {
        $totalIn = StockMovement::where('product_id', $this->id)
            ->where('type', 'entree')
            ->sum('quantity');
            
        $totalOut = StockMovement::where('product_id', $this->id)
            ->where('type', 'sortie')
            ->sum('quantity');
            
        $calculatedStock = $totalIn - $totalOut;
        
        return [
            'current_stock' => $this->stock,
            'calculated_stock' => $calculatedStock,
            'total_in' => $totalIn,
            'total_out' => $totalOut,
            'is_consistent' => $this->stock == $calculatedStock,
            'difference' => $this->stock - $calculatedStock,
        ];
    }
}