<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('categories.create') ?? false;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'unique:categories,name',
            ],

            'slug' => [
                'nullable',
                'string',
                'max:255',
                'alpha_dash',
                'unique:categories,slug',
            ],

            'description' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'is_active' => [
                'nullable',
                'boolean',
            ],

            'sort_order' => [
                'nullable',
                'integer',
                'min:0',
                'max:2147483647',
            ],
        ];
    }
}
