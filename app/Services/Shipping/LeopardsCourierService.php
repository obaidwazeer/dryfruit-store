<?php

namespace App\Services\Shipping;

use App\Models\Order;
use App\Models\Shipment;
use RuntimeException;

class LeopardsCourierService implements CourierInterface
{
    public function createShipment(Order $order): Shipment
    {
        /*
        |--------------------------------------------------------------------------
        | Leopards API integration will be implemented here.
        |--------------------------------------------------------------------------
        |
        | We will NOT call the Leopards API yet.
        |
        */

        throw new RuntimeException(
            'Leopards shipment integration is not configured yet.'
        );
    }

    public function track(string $trackingNumber): array
    {
        /*
        |--------------------------------------------------------------------------
        | Leopards tracking API will be implemented here.
        |--------------------------------------------------------------------------
        */

        throw new RuntimeException(
            'Leopards tracking integration is not configured yet.'
        );
    }

    public function cancel(string $trackingNumber): bool
    {
        /*
        |--------------------------------------------------------------------------
        | Leopards cancellation API will be implemented here.
        |--------------------------------------------------------------------------
        */

        throw new RuntimeException(
            'Leopards cancellation integration is not configured yet.'
        );
    }
}
