<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Shipment extends Model
{
    protected $fillable = [
        'order_id',
        'fulfillment_method',
        'courier',
        'tracking_number',
        'status',
        'tracking_url',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'shipped_at' => 'datetime',
            'delivered_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Fulfillment Methods
    |--------------------------------------------------------------------------
    */

    public const FULFILLMENT_MANUAL = 'manual';

    public const FULFILLMENT_API = 'api';

    public const FULFILLMENT_METHODS = [
        self::FULFILLMENT_MANUAL,
        self::FULFILLMENT_API,
    ];

    /*
    |--------------------------------------------------------------------------
    | Shipment Statuses
    |--------------------------------------------------------------------------
    */

    public const STATUS_PENDING = 'pending';

    public const STATUS_READY = 'ready';

    public const STATUS_SHIPPED = 'shipped';

    public const STATUS_IN_TRANSIT = 'in_transit';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUS_CANCELLED = 'cancelled';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_READY,
        self::STATUS_SHIPPED,
        self::STATUS_IN_TRANSIT,
        self::STATUS_DELIVERED,
        self::STATUS_CANCELLED,
    ];

    public const STATUS_TRANSITIONS = [

        self::STATUS_PENDING => [
            self::STATUS_READY,
            self::STATUS_CANCELLED,
        ],

        self::STATUS_READY => [
            self::STATUS_SHIPPED,
            self::STATUS_CANCELLED,
        ],

        self::STATUS_SHIPPED => [
            self::STATUS_IN_TRANSIT,
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
        ],

        self::STATUS_IN_TRANSIT => [
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
        ],

        self::STATUS_DELIVERED => [],

        self::STATUS_CANCELLED => [],
    ];

    public function canTransitionTo(string $newStatus): bool
{
    if ($this->status === $newStatus) {
        return true;
    }

    return in_array(
        $newStatus,
        self::STATUS_TRANSITIONS[$this->status] ?? [],
        true
    );
}
    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
