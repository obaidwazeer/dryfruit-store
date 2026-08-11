<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('products.update') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:100',
            ],

            'sku' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'unique:product_variants,sku',
            ],

            'weight_grams' => [
                'required',
                'integer',
                'min:1',
                'max:100000',
            ],

            'price' => [
                'required',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'compare_at_price' => [
                'nullable',
                'numeric',
                'min:0',
                'max:9999999999.99',
            ],

            'stock_quantity' => [
                'required',
                'integer',
                'min:0',
                'max:2147483647',
            ],

            'low_stock_threshold' => [
                'required',
                'integer',
                'min:0',
                'max:2147483647',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}
