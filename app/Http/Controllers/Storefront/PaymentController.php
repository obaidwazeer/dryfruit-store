<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Initiate payment.
     *
     * Keep your existing payment initiation logic here if you already
     * have additional payment methods/gateways implemented.
     */
    public function initiate(Request $request, Order $order)
    {
        /*
        |--------------------------------------------------------------------------
        | Bank Transfer
        |--------------------------------------------------------------------------
        |
        | If the order is being paid through bank transfer, find the
        | corresponding payment transaction and redirect the customer
        | to the bank transfer page.
        |
        */

        $transaction = PaymentTransaction::query()
            ->where('order_id', $order->id)
            ->where('provider', 'bank_transfer')
            ->latest('id')
            ->first();

        if (! $transaction) {
            abort(404, 'Bank transfer payment transaction not found.');
        }

        return redirect()->route(
            'storefront.payment.bank-transfer',
            [
                'transaction' => $transaction->transaction_reference,
            ]
        );
    }

    /**
     * Show bank transfer payment page.
     */
    public function bankTransfer(PaymentTransaction $transaction)
    {
        /*
        |--------------------------------------------------------------------------
        | Security / Validation
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $transaction->provider === 'bank_transfer',
            404
        );

        return view(
            'storefront.payment.bank-transfer',
            compact('transaction')
        );
    }

    /**
     * Submit bank transfer payment.
     */
    public function submitBankTransfer(
        Request $request,
        PaymentTransaction $transaction
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validate Payment Transaction
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $transaction->provider === 'bank_transfer',
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Validate Customer Submission
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'transaction_reference' => [
                'required',
                'string',
                'max:255',
            ],

            /*
             * Payment proof is optional.
             *
             * The previous flow allowed the customer to submit the
             * bank reference even when proof was not provided.
             */
            'payment_proof' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,webp,pdf',
                'max:5120',
            ],

            'notes' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Normalize Bank Transaction Reference
        |--------------------------------------------------------------------------
        */

        $bankTransactionReference = trim(
            $validated['transaction_reference']
        );

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Submission On Same Payment
        |--------------------------------------------------------------------------
        |
        | If this exact payment transaction has already received the same
        | bank transaction reference and is still pending, do not allow
        | another submission.
        |
        */

        if (
            ! empty($transaction->gateway_transaction_id) &&
            strcasecmp(
                trim($transaction->gateway_transaction_id),
                $bankTransactionReference
            ) === 0
        ) {
            if (
                $transaction->status ===
                PaymentTransaction::STATUS_PENDING
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'transaction_reference' => 'This transaction reference has already been submitted and is still pending verification.',
                    ]);
            }

            if (
                $transaction->status ===
                PaymentTransaction::STATUS_PAID
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'transaction_reference' => 'This transaction reference has already been verified and paid.',
                    ]);
            }

            if (
                $transaction->status ===
                PaymentTransaction::STATUS_PROCESSING
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'transaction_reference' => 'This transaction reference is already being processed.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Prevent Same Bank Reference Across Different Payments
        |--------------------------------------------------------------------------
        |
        | A customer must not be able to reuse the same bank transaction
        | reference for another order/payment while the first transaction
        | is still being verified.
        |
        */

        $existingTransaction = PaymentTransaction::query()
            ->where('gateway_transaction_id', $bankTransactionReference)
            ->where('id', '!=', $transaction->id)
            ->whereIn('status', [
                PaymentTransaction::STATUS_PENDING,
                PaymentTransaction::STATUS_PROCESSING,
                PaymentTransaction::STATUS_PAID,
            ])
            ->first();

        if ($existingTransaction) {

            if (
                $existingTransaction->status ===
                PaymentTransaction::STATUS_PENDING
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'transaction_reference' => 'A transaction with this reference number has already been submitted and is still pending verification.',
                    ]);
            }

            if (
                $existingTransaction->status ===
                PaymentTransaction::STATUS_PROCESSING
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'transaction_reference' => 'A transaction with this reference number is already being processed.',
                    ]);
            }

            if (
                $existingTransaction->status ===
                PaymentTransaction::STATUS_PAID
            ) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'transaction_reference' => 'A transaction with this reference number has already been verified.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Store Payment Proof
        |--------------------------------------------------------------------------
        */

        $paymentProofPath = null;

        if ($request->hasFile('payment_proof')) {
            $paymentProofPath = $request
                ->file('payment_proof')
                ->store(
                    'payments/bank-transfer',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Existing Response Payload
        |--------------------------------------------------------------------------
        |
        | We don't have payment_proof or notes columns in
        | payment_transactions.
        |
        | Therefore we preserve the existing response payload and store
        | bank-transfer submission information there.
        |
        */

        $responsePayload = $transaction->response_payload ?? [];

        $responsePayload['bank_transfer'] = [
            'customer_transaction_reference' => $bankTransactionReference,

            'payment_proof' => $paymentProofPath,

            'notes' => $validated['notes'] ?? null,

            'submitted_at' => now()->toDateTimeString(),
        ];

        /*
        |--------------------------------------------------------------------------
        | Update Payment Transaction
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | transaction_reference remains:
        |
        | PAY-20260816020508-QRLJPNDE
        |
        | gateway_transaction_id becomes the customer's actual
        | bank transaction reference.
        |
        */

        $transaction->gateway_transaction_id =
            $bankTransactionReference;

        $transaction->response_payload =
            $responsePayload;

        /*
        |--------------------------------------------------------------------------
        | Bank Transfer Status
        |--------------------------------------------------------------------------
        |
        | Do NOT mark the transaction as paid.
        |
        | Admin must verify the bank transfer first.
        |
        */

        $transaction->status =
            PaymentTransaction::STATUS_PENDING;

        $transaction->save();

        /*
        |--------------------------------------------------------------------------
        | Redirect To Confirmation Page
        |--------------------------------------------------------------------------
        |
        | DO NOT redirect back to the bank transfer form.
        |
        | This was the problem in the previous implementation.
        |
        */

        return redirect()->route(
            'storefront.payment.bank-transfer.submitted',
            [
                'transaction' => $transaction->transaction_reference,
            ]
        );
    }

    /**
     * Show bank transfer submission confirmation page.
     */
    public function bankTransferSubmitted(
        PaymentTransaction $transaction
    ) {
        /*
        |--------------------------------------------------------------------------
        | Validate Transaction
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $transaction->provider === 'bank_transfer',
            404
        );

        return view(
            'storefront.payment.bank-transfer-submitted',
            compact('transaction')
        );
    }
}
