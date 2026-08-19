<?php

namespace App\Services\Shipping;

use App\Models\Order;
use App\Models\Shipment;

interface CourierInterface
{
    /**
     * Create a shipment with the courier.
     */
    public function createShipment(Order $order): Shipment;

    /**
     * Track an existing shipment.
     */
    public function track(string $trackingNumber): array;

    /**
     * Cancel an existing shipment.
     */
    public function cancel(string $trackingNumber): bool;
}
