<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentAssistanceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules.
     */
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
        ];
    }

    /**
     * Get custom validation messages.
     */
    public function messages(): array
    {
        return [
            'customer_name.required' => 'Please enter your name.',

            'customer_name.max' => 'Your name cannot exceed 255 characters.',

            'customer_email.email' => 'Please enter a valid email address.',

            'customer_phone.required' => 'Please enter your phone number.',

            'customer_phone.max' => 'The phone number cannot exceed 30 characters.',
        ];
    }
}
