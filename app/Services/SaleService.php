<?php

namespace App\Services;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Support\Facades\DB;

class SaleService
{
    /**
     * Crée une vente complète (items + mouvements de stock) dans une transaction.
     *
     * @throws \Exception si stock insuffisant ou produit invalide
     */
    public function create(array $validated, int $tenantId, int $userId): Sale
    {
        return DB::transaction(function () use ($validated, $tenantId, $userId) {
            $sale = Sale::create([
                'client_id'   => $validated['client_id'] ?? null,
                'user_id'     => $userId,
                'total_price' => 0,
                'tenant_id'   => $tenantId,
            ]);

            $clientName = $this->resolveClientName($validated['client_id'] ?? null);
            $grandTotal = 0;

            foreach ($validated['products'] as $item) {
                /** @var Product $product */
                $product = Product::lockForUpdate()->find($item['product_id']);

                if (!$product) {
                    throw new \Exception("Produit introuvable : #{$item['product_id']}");
                }
                if ($product->tenant_id !== $tenantId) {
                    throw new \Exception("Le produit \"{$product->name}\" n'appartient pas à votre boutique.");
                }
                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Stock insuffisant pour \"{$product->name}\". Disponible : {$product->stock}, demandé : {$item['quantity']}.");
                }

                $unitPrice    = $item['unit_price'];
                $qty          = $item['quantity'];
                $stockAfter   = $product->stock - $qty;

                StockMovement::create([
                    'product_id'         => $product->id,
                    'type'               => 'sortie',
                    'quantity'           => $qty,
                    'purchase_price'     => $product->purchase_price,
                    'sale_price'         => $unitPrice,
                    'stock_after'        => $stockAfter,
                    'motif'              => "Vente #{$sale->id} à {$clientName}",
                    'reference_document' => "VTE-{$sale->id}",
                    'user_id'            => $userId,
                    'tenant_id'          => $tenantId,
                ]);

                SaleItem::create([
                    'sale_id'     => $sale->id,
                    'product_id'  => $product->id,
                    'quantity'    => $qty,
                    'unit_price'  => $unitPrice,
                    'total_price' => $unitPrice * $qty,
                    'tenant_id'   => $tenantId,
                ]);

                $product->decrement('stock', $qty);

                $grandTotal += $unitPrice * $qty;
            }

            $discount = $validated['discount'] ?? 0;
            $sale->update(['total_price' => max(0, $grandTotal - $discount)]);

            return $sale->fresh(['items.product', 'client']);
        });
    }

    /**
     * Annule une vente et restitue le stock.
     *
     * @throws \Exception si la vente est déjà annulée
     */
    public function cancel(Sale $sale, int $userId): void
    {
        if ($sale->status === 'cancelled') {
            throw new \Exception('Cette vente est déjà annulée.');
        }

        DB::transaction(function () use ($sale, $userId) {
            foreach ($sale->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if (!$product) continue;

                $stockAfter = $product->stock + $item->quantity;

                StockMovement::create([
                    'product_id'         => $product->id,
                    'type'               => 'entree',
                    'quantity'           => $item->quantity,
                    'purchase_price'     => $product->purchase_price,
                    'sale_price'         => $item->unit_price,
                    'stock_after'        => $stockAfter,
                    'motif'              => "Annulation vente #{$sale->id}",
                    'reference_document' => "CANCEL-{$sale->id}",
                    'user_id'            => $userId,
                    'tenant_id'          => $sale->tenant_id,
                ]);

                $product->increment('stock', $item->quantity);
            }

            $sale->update(['status' => 'cancelled']);
        });
    }

    private function resolveClientName(?int $clientId): string
    {
        if (!$clientId) return 'Client comptoir';

        $client = \App\Models\Client::find($clientId);
        return $client?->name ?? 'Client';
    }
}
