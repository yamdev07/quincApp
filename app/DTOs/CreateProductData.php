<?php

namespace App\DTOs;

final readonly class CreateProductData
{
    public function __construct(
        public string  $name,
        public int     $stock,
        public float   $purchasePrice,
        public float   $salePrice,
        public int     $categoryId,
        public int     $supplierId,
        public ?int    $stockAlert = null,
        public ?string $description = null,
    ) {}

    public static function fromArray(array $validated): self
    {
        return new self(
            name:          $validated['name'],
            stock:         (int) ($validated['stock'] ?? 0),
            purchasePrice: (float) $validated['purchase_price'],
            salePrice:     (float) $validated['sale_price'],
            categoryId:    (int) $validated['category_id'],
            supplierId:    (int) $validated['supplier_id'],
            stockAlert:    isset($validated['stock_alert']) ? (int) $validated['stock_alert'] : null,
            description:   $validated['description'] ?? null,
        );
    }
}
