<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBankTransferPaymentRequest;
use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BankTransferController extends Controller
{
    public function show(
        Request $request,
        Order $order,
        PaymentTransaction $transaction
    ): View {
        abort_unless(
            session('last_order_id') === $order->id,
            404
        );

        abort_unless(
            $transaction->order_id === $order->id,
            404
        );

        abort_unless(
            $transaction->provider === 'bank_transfer',
            404
        );

        return view(
            'storefront.payment.bank-transfer',
            compact(
                'order',
                'transaction'
            )
        );
    }

    public function submit(
        StoreBankTransferPaymentRequest $request,
        Order $order,
        PaymentTransaction $transaction
    ): RedirectResponse {
        abort_unless(
            session('last_order_id') === $order->id,
            404
        );

        abort_unless(
            $transaction->order_id === $order->id,
            404
        );

        abort_unless(
            $transaction->provider === 'bank_transfer',
            404
        );

        if (
            $transaction->status ===
            PaymentTransaction::STATUS_PAID
        ) {
            return back()->withErrors([
                'payment' => 'This payment has already been verified.',
            ]);
        }

        if (
            $transaction->status ===
            PaymentTransaction::STATUS_PENDING_VERIFICATION
        ) {
            return back()->withErrors([
                'payment' => 'Your payment details have already been submitted and are awaiting verification.',
            ]);
        }

        if (
            $transaction->status ===
            PaymentTransaction::STATUS_FAILED
        ) {
            return back()->withErrors([
                'payment' => 'This payment transaction has been rejected. Please contact support.',
            ]);
        }

        $validated = $request->validated();

        /*
        |--------------------------------------------------------------------------
        | Validate Submitted Amount
        |--------------------------------------------------------------------------
        */

        if (
            (float) $validated['submitted_amount'] !==
            (float) $transaction->amount
        ) {
            return back()
                ->withInput()
                ->withErrors([
                    'submitted_amount' => 'The transferred amount must exactly match the order amount.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Bank Transaction Reference
        |--------------------------------------------------------------------------
        */

        $alreadyUsed = PaymentTransaction::query()
            ->where('gateway_transaction_id', $validated['gateway_transaction_id'])
            ->where('id', '!=', $transaction->id)
            ->exists();

        if ($alreadyUsed) {
            return back()
                ->withInput()
                ->withErrors([
                    'gateway_transaction_id' => 'This bank transaction reference has already been submitted.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Upload Payment Proof
        |--------------------------------------------------------------------------
        */

        $proofPath = $transaction->proof_path;

        if ($request->hasFile('payment_proof')) {

            $proofPath = $request
                ->file('payment_proof')
                ->store(
                    'payment-proofs',
                    'private'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Update Transaction
        |--------------------------------------------------------------------------
        */

        $transaction->update([
            'payer_name' => $validated['payer_name'],

            'submitted_amount' => $validated['submitted_amount'],

            'gateway_transaction_id' => $validated['gateway_transaction_id'],

            'proof_path' => $proofPath,

            'status' => PaymentTransaction::STATUS_PENDING_VERIFICATION,

            'submitted_at' => now(),

            'verification_note' => null,
        ]);

        return redirect()
            ->route(
                'storefront.payment.bank-transfer.submitted',
                [
                    'order' => $order,
                    'transaction' => $transaction,
                ]
            );
    }

    public function submitted(
        Request $request,
        Order $order,
        PaymentTransaction $transaction
    ): View {
        abort_unless(
            session('last_order_id') === $order->id,
            404
        );

        abort_unless(
            $transaction->order_id === $order->id,
            404
        );

        return view(
            'storefront.payment.bank-transfer-submitted',
            compact(
                'order',
                'transaction'
            )
        );
    }


    public function bankTransfer(
        PaymentTransaction $transaction
    ): View {
        $order = $transaction->order;

        abort_unless(
            session('last_order_id') === $order->id,
            404
        );

        abort_unless(
            $transaction->provider === 'bank_transfer',
            404
        );

        abort_unless(
            $order->payment_method === 'online',
            404
        );

        abort_unless(
            $order->payment_provider === 'bank_transfer',
            404
        );

        abort_unless(
            in_array(
                $transaction->status,
                [
                    PaymentTransaction::STATUS_PENDING,
                    PaymentTransaction::STATUS_PROCESSING,
                ],
                true
            ),
            404
        );

        return view(
            'storefront.payment.bank-transfer',
            compact(
                'order',
                'transaction'
            )
        );
    }
}
