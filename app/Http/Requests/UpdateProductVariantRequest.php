<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductVariantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('products.update') ?? false;
    }

    public function rules(): array
    {
        $variant = $this->route('variant');

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
                Rule::unique('product_variants', 'sku')
                    ->ignore($variant),
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
