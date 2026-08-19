<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipmentRequest;
use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ShipmentController extends Controller
{
    /**
     * Create a shipment.
     */
    public function store(
        StoreShipmentRequest $request,
        Order $order
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Prevent duplicate shipment
        |--------------------------------------------------------------------------
        */

        if ($order->shipment) {
            return back()
                ->withErrors([
                    'shipment' => 'This order already has a shipment.',
                ]);
        }

        $data = $request->validated();

        DB::transaction(function () use ($data, $order) {

            /*
            |--------------------------------------------------------------------------
            | Shipment dates
            |--------------------------------------------------------------------------
            */

            if (
                in_array($data['status'], [
                    Shipment::STATUS_SHIPPED,
                    Shipment::STATUS_IN_TRANSIT,
                    Shipment::STATUS_DELIVERED,
                ], true)
            ) {
                $data['shipped_at'] = now();
            }

            if ($data['status'] === Shipment::STATUS_DELIVERED) {
                $data['delivered_at'] = now();
            }

            /*
            |--------------------------------------------------------------------------
            | Create shipment
            |--------------------------------------------------------------------------
            */

            $shipment = $order->shipment()->create($data);

            /*
            |--------------------------------------------------------------------------
            | Update order status
            |--------------------------------------------------------------------------
            */

            $this->syncOrderStatus(
                $order,
                $shipment->status
            );
        });

        return redirect()
            ->route(
                'admin.admin.orders.show',
                $order
            )
            ->with(
                'success',
                'Shipment created successfully.'
            );
    }

    /**
     * Update shipment.
     */
    public function update(
        Request $request,
        Order $order,
        Shipment $shipment
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Make sure shipment belongs to this order
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $shipment->order_id === $order->id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Validate
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([

            'courier' => [
                'required',
                'string',
                'max:100',
            ],

            'tracking_number' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
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

        ]);

        DB::transaction(function () use (
            $validated,
            $order,
            $shipment
        ) {

            /*
            |--------------------------------------------------------------------------
            | Update shipment information
            |--------------------------------------------------------------------------
            */

            $shipment->courier =
                $validated['courier'];

            $shipment->tracking_number =
                $validated['tracking_number'] ?? null;

            $shipment->tracking_url =
                $validated['tracking_url'] ?? null;

            $shipment->notes =
                $validated['notes'] ?? null;

            $shipment->status =
                $validated['status'];

            /*
            |--------------------------------------------------------------------------
            | Shipment dates
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $shipment->status,
                    [
                        Shipment::STATUS_SHIPPED,
                        Shipment::STATUS_IN_TRANSIT,
                        Shipment::STATUS_DELIVERED,
                    ],
                    true
                )
            ) {
                if (! $shipment->shipped_at) {
                    $shipment->shipped_at = now();
                }
            }

            if (
                $shipment->status ===
                Shipment::STATUS_DELIVERED
            ) {
                if (! $shipment->delivered_at) {
                    $shipment->delivered_at = now();
                }
            } else {
                $shipment->delivered_at = null;
            }

            /*
            |--------------------------------------------------------------------------
            | Save shipment
            |--------------------------------------------------------------------------
            */

            $shipment->save();

            /*
            |--------------------------------------------------------------------------
            | Synchronize order status
            |--------------------------------------------------------------------------
            */

            $this->syncOrderStatus(
                $order,
                $shipment->status
            );
        });

        return back()->with(
            'success',
            'Shipment updated successfully.'
        );
    }

    /**
     * Update shipment status only.
     */
    public function updateStatus(
        Request $request,
        Order $order,
        Shipment $shipment
    ): RedirectResponse {

        abort_unless(
            $shipment->order_id === $order->id,
            404
        );

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(Shipment::STATUSES),
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $order,
            $shipment
        ) {

            $shipment->status =
                $validated['status'];

            /*
            |--------------------------------------------------------------------------
            | Shipment dates
            |--------------------------------------------------------------------------
            */

            if (
                in_array(
                    $shipment->status,
                    [
                        Shipment::STATUS_SHIPPED,
                        Shipment::STATUS_IN_TRANSIT,
                        Shipment::STATUS_DELIVERED,
                    ],
                    true
                )
            ) {
                if (! $shipment->shipped_at) {
                    $shipment->shipped_at = now();
                }
            }

            if (
                $shipment->status ===
                Shipment::STATUS_DELIVERED
            ) {
                if (! $shipment->delivered_at) {
                    $shipment->delivered_at = now();
                }
            } else {
                $shipment->delivered_at = null;
            }

            $shipment->save();

            /*
            |--------------------------------------------------------------------------
            | Synchronize order
            |--------------------------------------------------------------------------
            */

            $this->syncOrderStatus(
                $order,
                $shipment->status
            );
        });

        return back()->with(
            'success',
            'Shipment status updated successfully.'
        );
    }

    /**
     * Synchronize shipment status with order status.
     */
    private function syncOrderStatus(
        Order $order,
        string $shipmentStatus
    ): void {

        $orderStatus = match ($shipmentStatus) {

            Shipment::STATUS_READY => Order::STATUS_PROCESSING,

            Shipment::STATUS_SHIPPED,
            Shipment::STATUS_IN_TRANSIT => Order::STATUS_SHIPPED,

            Shipment::STATUS_DELIVERED => Order::STATUS_DELIVERED,

            Shipment::STATUS_CANCELLED => Order::STATUS_CANCELLED,

            Shipment::STATUS_PENDING => null,

            default => null,
        };

        if ($orderStatus === null) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Don't update unnecessarily
        |--------------------------------------------------------------------------
        */

        if ($order->status === $orderStatus) {
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Update order
        |--------------------------------------------------------------------------
        */

        $order->update([
            'status' => $orderStatus,
        ]);
    }
}
