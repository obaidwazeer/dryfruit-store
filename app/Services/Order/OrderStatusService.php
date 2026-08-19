<?php

namespace App\Services\Storefront;

use App\Models\Order;
use App\Models\Shipment;

class ShipmentOrderStatusService
{
    /**
     * Synchronize the order status according to shipment status.
     */
    public function synchronize(
        Order $order,
        Shipment $shipment
    ): void {

        $orderStatus = match ($shipment->status) {

            /*
            |--------------------------------------------------------------------------
            | Shipment Pending
            |--------------------------------------------------------------------------
            */

            Shipment::STATUS_PENDING => null,

            /*
            |--------------------------------------------------------------------------
            | Shipment Ready
            |--------------------------------------------------------------------------
            */

            Shipment::STATUS_READY => Order::STATUS_PROCESSING,

            /*
            |--------------------------------------------------------------------------
            | Shipment Shipped
            |--------------------------------------------------------------------------
            */

            Shipment::STATUS_SHIPPED,
            Shipment::STATUS_IN_TRANSIT => Order::STATUS_SHIPPED,

            /*
            |--------------------------------------------------------------------------
            | Shipment Delivered
            |--------------------------------------------------------------------------
            */

            Shipment::STATUS_DELIVERED => Order::STATUS_DELIVERED,

            /*
            |--------------------------------------------------------------------------
            | Shipment Cancelled
            |--------------------------------------------------------------------------
            */

            Shipment::STATUS_CANCELLED => Order::STATUS_CANCELLED,

            default => null,
        };

        /*
        |--------------------------------------------------------------------------
        | No Order Status Change Required
        |--------------------------------------------------------------------------
        */

        if ($orderStatus === null) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Already At Desired Status
        |--------------------------------------------------------------------------
        */

        if ($order->status === $orderStatus) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Update Order
        |--------------------------------------------------------------------------
        |
        | Shipment status is an operational fulfillment event.
        | Therefore we explicitly synchronize the order status here.
        |
        */

        $order->update([
            'status' => $orderStatus,
        ]);
    }
}
