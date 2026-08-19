<?php

namespace App\Http\Requests;

use App\Models\Shipment;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'courier' => [
                'required',
                'string',
                'max:50',
            ],

            'tracking_number' => [
                'nullable',
                'string',
                'max:255',
                'unique:shipments,tracking_number',
            ],

            'status' => [
                'required',
                'string',
                Rule::in(Shipment::STATUSES),
            ],

            'tracking_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:5000',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'courier.required' => 'Please select a courier.',
            'tracking_number.unique' => 'This tracking number is already assigned to another shipment.',
            'tracking_url.url' => 'Please enter a valid tracking URL.',
            'status.in' => 'The selected shipment status is invalid.',
        ];
    }
}
