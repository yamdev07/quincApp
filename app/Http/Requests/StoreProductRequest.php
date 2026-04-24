<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canManageStock();
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'stock'          => 'required|integer|min:0',
            'stock_alert'    => 'nullable|integer|min:0',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price'     => 'required|numeric|min:0',
            'description'    => 'nullable|string|max:1000',
            'category_id'    => 'required|exists:categories,id',
            'supplier_id'    => 'required|exists:suppliers,id',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'           => 'Le nom du produit est obligatoire.',
            'stock.required'          => 'La quantité en stock est obligatoire.',
            'stock.integer'           => 'Le stock doit être un nombre entier.',
            'purchase_price.required' => 'Le prix d\'achat est obligatoire.',
            'sale_price.required'     => 'Le prix de vente est obligatoire.',
            'category_id.required'    => 'La catégorie est obligatoire.',
            'category_id.exists'      => 'La catégorie sélectionnée est invalide.',
            'supplier_id.required'    => 'Le fournisseur est obligatoire.',
            'supplier_id.exists'      => 'Le fournisseur sélectionné est invalide.',
        ];
    }
}
