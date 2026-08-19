<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCheckoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'customer_email' => [
                'nullable',
                'email',
                'max:255',
            ],

            'customer_phone' => [
                'required',
                'string',
                'max:30',
            ],

            'shipping_address' => [
                'required',
                'string',
                'max:1000',
            ],

            'shipping_city' => [
                'required',
                'string',
                'max:100',
            ],

            'shipping_postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],

            'customer_notes' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'payment_method' => [
                'required',
                'string',
                Rule::in([
                    'cod',
                    'online',
                ]),
            ],

            'payment_provider' => [
                'nullable',
                'string',
                Rule::in([
                    'easypaisa',
                    'jazzcash',
                    'bank_transfer',
                ]),
                'required_if:payment_method,online',
            ],
        ];
    }
}
