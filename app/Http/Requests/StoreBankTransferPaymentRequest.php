<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBankTransferPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'submitted_amount' => [
                'required',
                'numeric',
                'min:0.01',
            ],

            'gateway_transaction_id' => [
                'required',
                'string',
                'max:150',
            ],

            'payment_proof' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],
        ];
    }
}
