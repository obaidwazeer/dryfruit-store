<?php

namespace App\Services\Payments;

use App\Contracts\Payments\PaymentGatewayInterface;
use RuntimeException;

class PaymentGatewayManager
{
    public function __construct(
        private readonly EasypaisaGateway $easypaisa,
        private readonly JazzCashGateway $jazzcash,
        private readonly BankTransferGateway $bankTransfer,
    ) {}

    public function driver(
        string $provider
    ): PaymentGatewayInterface {

        return match ($provider) {

            'easypaisa' => $this->easypaisa,

            'jazzcash' => $this->jazzcash,

            'bank_transfer' => $this->bankTransfer,

            default => throw new RuntimeException(
                "Unsupported payment provider: {$provider}"
            ),
        };
    }
}
