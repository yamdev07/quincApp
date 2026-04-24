<?php

namespace App\DTOs;

final readonly class SaleItemData
{
    public function __construct(
        public int   $productId,
        public int   $quantity,
        public float $unitPrice,
    ) {}

    public static function fromArray(array $item): self
    {
        return new self(
            productId: (int) $item['product_id'],
            quantity:  (int) $item['quantity'],
            unitPrice: (float) $item['unit_price'],
        );
    }
}
