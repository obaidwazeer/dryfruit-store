<?php

namespace App\Services\Shipping;

use App\Models\Order;
use App\Models\Shipment;

class ManualCourierService implements CourierInterface
{
    /**
     * Manual fulfillment does not call an external API.
     *
     * The admin creates the shipment and later enters
     * the tracking information received from the courier.
     */
    public function createShipment(Order $order): Shipment
    {
        return $order->shipment()->create([
            'courier' => 'manual',
            'status' => Shipment::STATUS_PENDING,
        ]);
    }

    /**
     * Tracking is handled manually for now.
     */
    public function track(string $trackingNumber): array
    {
        return [
            'tracking_number' => $trackingNumber,
            'status' => 'manual_tracking',
            'message' => 'Tracking information is managed manually.',
        ];
    }

    /**
     * Cancellation is handled manually.
     */
    public function cancel(string $trackingNumber): bool
    {
        return true;
    }
}
