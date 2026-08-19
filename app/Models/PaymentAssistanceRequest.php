<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentAssistanceRequest extends Model
{
    protected $fillable = [
        'reference',
        'customer_name',
        'customer_email',
        'customer_phone',
        'cart',
        'subtotal',
        'reason',
        'status',
        'admin_notes',
    ];

    protected function casts(): array
    {
        return [
            'cart' => 'array',
            'subtotal' => 'decimal:2',
        ];
    }
}
