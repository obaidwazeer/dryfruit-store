<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\Shipment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display the order listing.
     */
    public function index(Request $request): View
    {
        $orders = Order::query()
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {

                    $search = trim(
                        $request->input('search')
                    );

                    $query->where(function ($query) use ($search) {

                        $query
                            ->where(
                                'order_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'customer_name',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'customer_phone',
                                'like',
                                "%{$search}%"
                            );
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->input('status')
                )
            )
            ->when(
                $request->filled('payment_status'),
                fn ($query) => $query->where(
                    'payment_status',
                    $request->input('payment_status')
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view(
            'admin.orders.index',
            compact('orders')
        );
    }

    /**
     * Display a single order.
     */
    public function show(Order $order): View
    {
        $order->load([
            'items',
            'items.product',
            'items.variant',
            'shipment',
        ]);

        return view(
            'admin.orders.show',
            compact('order')
        );
    }

    /**
     * Update order status.
     *
     * Order status and shipment status are intentionally
     * separate concepts.
     */
    public function updateStatus(
        Request $request,
        Order $order
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in(Order::STATUSES),
            ],
        ]);

        $newStatus = $validated['status'];

        /*
        |--------------------------------------------------------------------------
        | Nothing To Change
        |--------------------------------------------------------------------------
        */

        if ($newStatus === $order->status) {
            return back()->with(
                'success',
                'Order status is already set to '
                    .ucfirst($newStatus)
                    .'.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Order Status Transition
        |--------------------------------------------------------------------------
        |
        | This uses the transition rules already defined inside
        | the Order model.
        |
        */

        if (! $order->canTransitionTo($newStatus)) {
            return back()
                ->withInput()
                ->withErrors([
                    'status' => sprintf(
                        'Order cannot be changed from %s to %s.',
                        ucfirst($order->status),
                        ucfirst($newStatus)
                    ),
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Shipment Validation
        |--------------------------------------------------------------------------
        |
        | Once an order reaches the shipping stage, there must
        | be a shipment associated with it.
        |
        */

        if (
            $newStatus === Order::STATUS_SHIPPED &&
            ! $order->shipment
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'status' => 'A shipment must be created before the order can be marked as shipped.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Delivered Validation
        |--------------------------------------------------------------------------
        |
        | An order should not be marked delivered while its shipment
        | is still pending, ready, shipped, or in transit.
        |
        */

        if ($newStatus === Order::STATUS_DELIVERED) {

            if (! $order->shipment) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'status' => 'A shipment must be created before the order can be marked as delivered.',
                    ]);
            }

            if (
                $order->shipment->status
                !== Shipment::STATUS_DELIVERED
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'status' => 'The shipment must be marked as delivered before the order can be marked as delivered.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Update Order
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $order,
            $newStatus
        ) {

            $order->update([
                'status' => $newStatus,
            ]);
        });

        return back()->with(
            'success',
            'Order status updated successfully.'
        );
    }

    /**
     * Update payment status.
     *
     * Payment status is intentionally independent from
     * order/shipment status.
     */
    public function updatePaymentStatus(
        Request $request,
        Order $order
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'payment_status' => [
                'required',
                Rule::in([
                    'pending',
                    'paid',
                    'failed',
                ]),
            ],
        ]);

        $newPaymentStatus = $validated['payment_status'];

        /*
        |--------------------------------------------------------------------------
        | Nothing To Change
        |--------------------------------------------------------------------------
        */

        if ($newPaymentStatus === $order->payment_status) {
            return back()->with(
                'success',
                'Payment status is already set to '
                    .ucfirst($newPaymentStatus)
                    .'.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Payment Status
        |--------------------------------------------------------------------------
        */

        DB::transaction(function () use (
            $order,
            $newPaymentStatus
        ) {

            $order->update([
                'payment_status' => $newPaymentStatus,
            ]);
        });

        return back()->with(
            'success',
            'Payment status updated successfully.'
        );
    }

    public function approvePayment(
        Order $order,
        PaymentTransaction $transaction
    ): RedirectResponse {
        abort_unless(
            $transaction->order_id === $order->id,
            404
        );

        if (
            $transaction->status !==
            PaymentTransaction::STATUS_PENDING_VERIFICATION
        ) {
            return back()->withErrors([
                'payment' => 'Only payments awaiting verification can be approved.',
            ]);
        }

        DB::transaction(function () use ($order, $transaction) {

            $transaction->update([
                'status' => PaymentTransaction::STATUS_PAID,
                'paid_at' => now(),
                'failed_at' => null,
                'verification_note' => null,
            ]);

            $order->update([
                'payment_status' => Order::PAYMENT_PAID,
            ]);
        });

        return back()->with(
            'success',
            'Payment has been verified successfully.'
        );
    }

    public function rejectPayment(
        Request $request,
        Order $order,
        PaymentTransaction $transaction
    ): RedirectResponse {
        abort_unless(
            $transaction->order_id === $order->id,
            404
        );

        $validated = $request->validate([
            'verification_note' => [
                'required',
                'string',
                'max:2000',
            ],
        ]);

        if (
            $transaction->status !==
            PaymentTransaction::STATUS_PENDING_VERIFICATION
        ) {
            return back()->withErrors([
                'payment' => 'Only payments awaiting verification can be rejected.',
            ]);
        }

        DB::transaction(function () use (
            $order,
            $transaction,
            $validated
        ) {

            $transaction->update([
                'status' => PaymentTransaction::STATUS_FAILED,
                'failed_at' => now(),
                'verification_note' => $validated['verification_note'],
            ]);

            $order->update([
                'payment_status' => Order::PAYMENT_FAILED,
            ]);
        });

        return back()->with(
            'success',
            'Payment has been rejected.'
        );
    }

    public function paymentProof(
        Order $order,
        PaymentTransaction $transaction
    ) {
        abort_unless(
            $transaction->order_id === $order->id,
            404
        );

        abort_unless(
            $transaction->proof_path,
            404
        );

        abort_unless(
            Storage::disk('private')->exists(
                $transaction->proof_path
            ),
            404
        );

        return Storage::disk('private')->response(
            $transaction->proof_path
        );
    }
}
