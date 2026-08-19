<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Statuses
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_PAID = 'paid';

    public const STATUS_FAILED = 'failed';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'order_id',
        'provider',
        'transaction_reference',
        'gateway_transaction_id',
        'amount',
        'currency',
        'status',
        'response_code',
        'response_message',
        'request_payload',
        'response_payload',
        'initiated_at',
        'paid_at',
        'failed_at',
    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'amount' => 'decimal:2',

        'request_payload' => 'array',

        'response_payload' => 'array',

        'initiated_at' => 'datetime',

        'paid_at' => 'datetime',

        'failed_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Order Relationship
    |--------------------------------------------------------------------------
    */

    public function order(): BelongsTo
    {
        return $this->belongsTo(
            Order::class
        );
    }
}
