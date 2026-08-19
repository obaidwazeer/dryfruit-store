<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    public const STATUS_PENDING = 'pending';

    public const STATUS_CONFIRMED = 'confirmed';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_SHIPPED = 'shipped';

    public const STATUS_DELIVERED = 'delivered';

    public const STATUS_CANCELLED = 'cancelled';

    public const PAYMENT_PENDING = 'pending';

    public const PAYMENT_PAID = 'paid';

    public const PAYMENT_FAILED = 'failed';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_CONFIRMED,
        self::STATUS_PROCESSING,
        self::STATUS_SHIPPED,
        self::STATUS_DELIVERED,
        self::STATUS_CANCELLED,
    ];

    public const STATUS_TRANSITIONS = [
        self::STATUS_PENDING => [
            self::STATUS_CONFIRMED,
            self::STATUS_CANCELLED,
        ],

        self::STATUS_CONFIRMED => [
            self::STATUS_PROCESSING,
            self::STATUS_CANCELLED,
        ],

        self::STATUS_PROCESSING => [
            self::STATUS_SHIPPED,
            self::STATUS_CANCELLED,
        ],

        self::STATUS_SHIPPED => [
            self::STATUS_DELIVERED,
        ],

        self::STATUS_DELIVERED => [],

        self::STATUS_CANCELLED => [],
    ];

    public function canTransitionTo(string $newStatus): bool
    {
        if (! in_array($newStatus, self::STATUSES, true)) {
            return false;
        }

        if ($this->status === $newStatus) {
            return true;
        }

        return in_array(
            $newStatus,
            self::STATUS_TRANSITIONS[$this->status] ?? [],
            true
        );
    }

    public function isFinalStatus(): bool
    {
        return in_array(
            $this->status,
            [
                self::STATUS_DELIVERED,
                self::STATUS_CANCELLED,
            ],
            true
        );
    }

    protected $fillable = [
        'payment_provider',
        'customer_id',
        'order_number',
        'customer_name',
        'customer_email',
        'customer_phone',
        'shipping_address',
        'shipping_city',
        'shipping_postal_code',
        'subtotal',
        'shipping_fee',
        'discount',
        'total',
        'status',
        'payment_method',
        'payment_status',
        'customer_notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'shipping_fee' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    public function paymentTransactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public function latestPaymentTransaction()
    {
        return $this->hasOne(PaymentTransaction::class)
            ->latestOfMany();
    }
}
