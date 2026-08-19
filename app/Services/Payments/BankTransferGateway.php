<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use App\Models\Order;
use App\Models\PaymentTransaction;

class BankTransferGateway implements PaymentGatewayInterface
{
    public function initiate(
        Order $order,
        PaymentTransaction $transaction
    ): array {
        return [
            'type' => 'manual',

            'provider' => 'bank_transfer',

            'transaction_reference' => $transaction->transaction_reference,

            'amount' => $transaction->amount,

            'currency' => $transaction->currency,

            'instructions' => [
                'Bank Name' => config('payment.bank_transfer.bank_name'),

                'Account Title' => config('payment.bank_transfer.account_title'),

                'Account Number' => config('payment.bank_transfer.account_number'),

                'IBAN' => config('payment.bank_transfer.iban'),
            ],
        ];
    }

    public function handleCallback(
        PaymentTransaction $transaction,
        array $payload
    ): array {
        /*
        |--------------------------------------------------------------------------
        | Bank transfer is manually verified.
        |--------------------------------------------------------------------------
        */

        return [
            'status' => PaymentTransaction::STATUS_PENDING,

            'message' => 'Bank transfer requires manual verification.',
        ];
    }

    public function verify(
        PaymentTransaction $transaction
    ): array {
        return [
            'status' => $transaction->status,

            'message' => 'Bank transfer requires manual verification.',
        ];
    }
}
