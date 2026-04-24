<?php

namespace App\DTOs;

final readonly class CreateSaleData
{
    /** @param SaleItemData[] $items */
    public function __construct(
        public array  $items,
        public float  $discount = 0.0,
        public ?int   $clientId = null,
    ) {}

    public static function fromArray(array $validated): self
    {
        return new self(
            items:    array_map(
                fn (array $item) => SaleItemData::fromArray($item),
                $validated['products'] ?? [],
            ),
            discount: (float) ($validated['discount'] ?? 0),
            clientId: isset($validated['client_id']) ? (int) $validated['client_id'] : null,
        );
    }
}
