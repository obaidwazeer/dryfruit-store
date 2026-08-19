<?php

namespace App\Contracts\Payments;

use App\Models\Order;
use App\Models\PaymentTransaction;

interface PaymentGatewayInterface
{
    /**
     * Initialize a payment.
     */
    public function initiate(
        Order $order,
        PaymentTransaction $transaction
    ): array;

    /**
     * Handle the response/callback from the gateway.
     */
    public function handleCallback(
        PaymentTransaction $transaction,
        array $payload
    ): array;

    /**
     * Verify payment status with the gateway.
     */
    public function verify(
        PaymentTransaction $transaction
    ): array;
}
