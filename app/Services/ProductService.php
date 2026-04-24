<?php

namespace App\Services;

use App\DTOs\CreateProductData;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ProductService
{
    /**
     * Appliquer recherche, filtres et tri sur une query Product.
     */
    public function applyFilters(Request $request): \Illuminate\Database\Eloquent\Builder
    {
        $query = Product::with(['category', 'supplier']);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sale_price', 'LIKE', "%{$search}%")
                  ->orWhere('purchase_price', 'LIKE', "%{$search}%")
                  ->orWhere('stock', 'LIKE', "%{$search}%");
            });
        }

        match ($request->input('filter')) {
            'low_stock'      => $query->whereColumn('stock', '<=', 'stock_alert')->where('stock', '>', 0),
            'out_of_stock'   => $query->where('stock', 0),
            'available'      => $query->where('stock', '>', 0),
            'cumulated'      => Schema::hasColumn('products', 'is_cumulated')
                                    ? $query->where('is_cumulated', true)
                                    : $query,
            'non_cumulated'  => Schema::hasColumn('products', 'has_been_cumulated')
                                    ? $query->where('has_been_cumulated', false)
                                    : $query,
            default          => null,
        };

        match ($request->input('sort_by', 'created_at')) {
            'name'          => $query->orderBy('name'),
            'stock'         => $query->orderBy('stock'),
            'sale_price'    => $query->orderBy('sale_price'),
            'profit_margin' => $query->orderByRaw('((sale_price - purchase_price) / purchase_price * 100) DESC'),
            default         => $query->orderBy('created_at', 'desc'),
        };

        return $query;
    }

    /**
     * Créer un nouveau produit ou cumuler sur un existant.
     */
    public function createOrCumulate(CreateProductData|array $data, int $tenantId, int $userId): array
    {
        if (is_array($data)) {
            $data = CreateProductData::fromArray($data);
        }

        $existing = Product::where('name', $data->name)
            ->where('category_id', $data->categoryId)
            ->where('supplier_id', $data->supplierId)
            ->first();

        return DB::transaction(function () use ($data, $tenantId, $userId, $existing) {
            if ($existing) {
                return $this->cumulateProduct($existing, $data, $userId);
            }
            return $this->createProduct($data, $userId);
        });
    }

    private function createProduct(CreateProductData $data, int $userId): array
    {
        $productData = [
            'name'           => $data->name,
            'stock'          => $data->stock,
            'quantity'       => $data->stock,
            'purchase_price' => $data->purchasePrice,
            'sale_price'     => $data->salePrice,
            'description'    => $data->description,
            'category_id'    => $data->categoryId,
            'supplier_id'    => $data->supplierId,
            'stock_alert'    => $data->stockAlert ?? 5,
        ];

        if (Schema::hasColumn('products', 'is_cumulated')) {
            $productData['is_cumulated'] = false;
        }

        $product = Product::create($productData);

        if ($data->stock > 0) {
            $this->recordMovement($product, 'entree', $data->stock, $data->purchasePrice, $data->salePrice, 'Stock initial', 'INITIAL-' . $product->id, $userId, doUpdateStock: false);
        }

        return ['type' => 'created', 'product' => $product];
    }

    private function cumulateProduct(Product $existing, CreateProductData $data, int $userId): array
    {
        $oldStock   = $existing->stock;
        $addedStock = $data->stock;
        $newStock   = $oldStock + $addedStock;

        $avgPurchase = $newStock > 0
            ? (($oldStock * $existing->purchase_price) + ($addedStock * $data->purchasePrice)) / $newStock
            : $data->purchasePrice;

        $updateData = [
            'stock'          => $newStock,
            'quantity'       => $newStock,
            'purchase_price' => round($avgPurchase, 2),
            'sale_price'     => $data->salePrice,
            'description'    => $data->description ?? $existing->description,
        ];

        if (Schema::hasColumn('products', 'is_cumulated')) {
            $updateData['is_cumulated'] = false;
        }

        $existing->update($updateData);

        if ($addedStock > 0) {
            $this->recordMovement(
                $existing, 'entree', $addedStock,
                $data->purchasePrice, $data->salePrice,
                'Réapprovisionnement', 'REAPPRO-' . time(),
                $userId, doUpdateStock: false
            );
        }

        return ['type' => 'restocked', 'product' => $existing];
    }

    /**
     * Créer un mouvement de stock. Si doUpdateStock = true, met à jour product.stock.
     */
    public function recordMovement(
        Product $product,
        string  $type,
        int     $quantity,
        float   $purchasePrice,
        float   $salePrice,
        string  $motif      = '',
        string  $reference  = '',
        int     $userId     = 0,
        bool    $doUpdateStock = true
    ): StockMovement {
        if ($type === 'sortie' && $product->stock < $quantity) {
            throw new \Exception("Stock insuffisant pour \"{$product->name}\". Disponible : {$product->stock}");
        }

        $stockAfter = $type === 'entree'
            ? $product->stock + $quantity
            : $product->stock - $quantity;

        $movement = StockMovement::create([
            'product_id'         => $product->id,
            'type'               => $type,
            'quantity'           => $quantity,
            'purchase_price'     => $purchasePrice,
            'sale_price'         => $salePrice,
            'stock_after'        => $doUpdateStock ? $stockAfter : $product->stock,
            'motif'              => $motif,
            'reference_document' => $reference,
            'user_id'            => $userId ?: auth()->id(),
            'tenant_id'          => $product->tenant_id,
        ]);

        if ($doUpdateStock) {
            $product->update(['stock' => $stockAfter, 'quantity' => $stockAfter]);
        }

        return $movement;
    }

    /**
     * Stats globales du tenant pour la liste produits.
     */
    public function globalStats(): array
    {
        return [
            'total'         => Product::count(),
            'total_stock'   => Product::sum('stock'),
            'total_value'   => Product::sum(DB::raw('sale_price * stock')),
            'multi_batches' => Product::withMultipleBatches()->count(),
        ];
    }
}
