<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PaymentTransactionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Show Transaction
    |--------------------------------------------------------------------------
    */

    public function show(
        PaymentTransaction $paymentTransaction
    ): View {

        $paymentTransaction->load([
            'order.items',
            'order.customer',
        ]);

        return view(
            'admin.payment-transactions.show',
            compact('paymentTransaction')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approve Payment
    |--------------------------------------------------------------------------
    */

    public function approve(
        PaymentTransaction $paymentTransaction
    ): RedirectResponse {

        DB::transaction(function () use ($paymentTransaction) {

            $paymentTransaction->refresh();

            /*
            |--------------------------------------------------------------------------
            | Prevent Double Approval
            |--------------------------------------------------------------------------
            */

            if (
                $paymentTransaction->status ===
                PaymentTransaction::STATUS_PAID
            ) {

                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Do Not Approve Failed Transaction Directly
            |--------------------------------------------------------------------------
            */

            if (
                $paymentTransaction->status ===
                PaymentTransaction::STATUS_FAILED
            ) {

                throw new \RuntimeException(
                    'A failed payment transaction cannot be approved directly.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Mark Transaction Paid
            |--------------------------------------------------------------------------
            */

            $paymentTransaction->update([

                'status' => PaymentTransaction::STATUS_PAID,

                'paid_at' => now(),

                'failed_at' => null,

                'response_message' => 'Payment manually verified and approved by admin.',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Order Payment Status
            |--------------------------------------------------------------------------
            */

            $paymentTransaction->order()->update([

                'payment_status' => Order::PAYMENT_PAID,
            ]);
        });

        return back()->with(
            'success',
            'Payment transaction approved successfully.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Reject Payment
    |--------------------------------------------------------------------------
    */

    public function reject(
        PaymentTransaction $paymentTransaction
    ): RedirectResponse {

        DB::transaction(function () use ($paymentTransaction) {

            $paymentTransaction->refresh();

            /*
            |--------------------------------------------------------------------------
            | Prevent Rejecting Already Paid Transaction
            |--------------------------------------------------------------------------
            */

            if (
                $paymentTransaction->status ===
                PaymentTransaction::STATUS_PAID
            ) {

                throw new \RuntimeException(
                    'A paid transaction cannot be rejected.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Mark Transaction Failed
            |--------------------------------------------------------------------------
            */

            $paymentTransaction->update([

                'status' => PaymentTransaction::STATUS_FAILED,

                'failed_at' => now(),

                'paid_at' => null,

                'response_message' => 'Payment manually rejected by admin.',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Order Payment Status
            |--------------------------------------------------------------------------
            */

            $paymentTransaction->order()->update([

                'payment_status' => Order::PAYMENT_FAILED,
            ]);
        });

        return back()->with(
            'success',
            'Payment transaction rejected successfully.'
        );
    }
}
