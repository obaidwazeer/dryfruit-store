<?php

namespace App\Services\Payments;

use App\Models\Order;
use App\Models\PaymentTransaction;
use Illuminate\Support\Str;
use RuntimeException;

class PaymentTransactionService
{
    public function create(
        Order $order,
        string $provider
    ): PaymentTransaction {

        if ($order->payment_method !== 'online') {
            throw new RuntimeException(
                'A payment transaction can only be created for online payments.'
            );
        }

        if ($order->payment_provider !== $provider) {
            throw new RuntimeException(
                'Payment provider does not match the order.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Reuse Existing Pending/Processing Transaction
        |--------------------------------------------------------------------------
        */

        $existingTransaction = $order
            ->paymentTransactions()
            ->whereIn('status', [
                PaymentTransaction::STATUS_PENDING,
                PaymentTransaction::STATUS_PROCESSING,
            ])
            ->latest()
            ->first();

        if ($existingTransaction) {
            return $existingTransaction;
        }

        /*
        |--------------------------------------------------------------------------
        | Create New Transaction
        |--------------------------------------------------------------------------
        */

        return $order->paymentTransactions()->create([
            'provider' => $provider,

            'transaction_reference' => $this->generateReference(),

            'amount' => $order->total,

            'currency' => 'PKR',

            'status' => PaymentTransaction::STATUS_PENDING,

            'initiated_at' => now(),
        ]);
    }

    private function generateReference(): string
    {
        do {
            $reference =
                'PAY-'.
                now()->format('YmdHis').
                '-'.
                strtoupper(Str::random(8));

        } while (
            PaymentTransaction::query()
                ->where(
                    'transaction_reference',
                    $reference
                )
                ->exists()
        );

        return $reference;
    }
}
