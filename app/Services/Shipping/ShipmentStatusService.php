<?php

namespace App\Services\Shipping;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

class ShipmentStatusService
{
    /**
     * Change shipment status and synchronize the related order.
     */
    public function updateStatus(
        Shipment $shipment,
        string $newStatus
    ): Shipment {

        if (! in_array($newStatus, Shipment::STATUSES, true)) {
            throw new InvalidArgumentException(
                "Invalid shipment status: {$newStatus}"
            );
        }

        if ($shipment->status === $newStatus) {
            return $shipment;
        }

        return DB::transaction(function () use (
            $shipment,
            $newStatus
        ) {

            $shipment->status = $newStatus;

            /*
            |--------------------------------------------------------------------------
            | Shipment timestamps
            |--------------------------------------------------------------------------
            */

            if (
                $newStatus === Shipment::STATUS_SHIPPED &&
                ! $shipment->shipped_at
            ) {
                $shipment->shipped_at = now();
            }

            if (
                $newStatus === Shipment::STATUS_DELIVERED &&
                ! $shipment->delivered_at
            ) {
                $shipment->delivered_at = now();
            }

            $shipment->save();

            /*
            |--------------------------------------------------------------------------
            | Synchronize Order
            |--------------------------------------------------------------------------
            */

            $this->synchronizeOrderStatus(
                $shipment,
                $newStatus
            );

            return $shipment->fresh();
        });
    }

    /**
     * Synchronize order fulfillment status with shipment status.
     */
    private function synchronizeOrderStatus(
        Shipment $shipment,
        string $shipmentStatus
    ): void {

        $order = $shipment->order;

        if (! $order) {
            return;
        }

        switch ($shipmentStatus) {

            case Shipment::STATUS_READY:

                if (
                    $order->status === Order::STATUS_PENDING ||
                    $order->status === Order::STATUS_CONFIRMED
                ) {
                    $order->update([
                        'status' => Order::STATUS_PROCESSING,
                    ]);
                }

                break;

            case Shipment::STATUS_SHIPPED:

            case Shipment::STATUS_IN_TRANSIT:

                if (
                    in_array(
                        $order->status,
                        [
                            Order::STATUS_PENDING,
                            Order::STATUS_CONFIRMED,
                            Order::STATUS_PROCESSING,
                        ],
                        true
                    )
                ) {
                    $order->update([
                        'status' => Order::STATUS_SHIPPED,
                    ]);
                }

                break;

            case Shipment::STATUS_DELIVERED:

                $order->update([
                    'status' => Order::STATUS_DELIVERED,
                ]);

                break;

            case Shipment::STATUS_CANCELLED:

                /*
                 * We intentionally do not automatically cancel
                 * the order here.
                 *
                 * Shipment cancellation and order cancellation
                 * can represent different business situations.
                 */

                break;
        }
    }
}
