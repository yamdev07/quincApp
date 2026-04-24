<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageSales();
    }

    public function rules(): array
    {
        return [
            'client_id'                  => 'nullable|exists:clients,id',
            'discount'                   => 'nullable|numeric|min:0',
            'products'                   => 'required|array|min:1',
            'products.*.product_id'      => 'required|exists:products,id',
            'products.*.quantity'        => 'required|integer|min:1',
            'products.*.unit_price'      => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'products.required'                  => 'Vous devez ajouter au moins un produit.',
            'products.min'                       => 'Vous devez ajouter au moins un produit.',
            'products.*.product_id.required'     => 'Chaque ligne doit avoir un produit.',
            'products.*.product_id.exists'       => 'Un produit sélectionné est invalide.',
            'products.*.quantity.required'       => 'La quantité est obligatoire.',
            'products.*.quantity.min'            => 'La quantité doit être au moins 1.',
            'products.*.unit_price.required'     => 'Le prix unitaire est obligatoire.',
            'products.*.unit_price.min'          => 'Le prix unitaire ne peut pas être négatif.',
        ];
    }
}
