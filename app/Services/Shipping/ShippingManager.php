<?php

namespace App\Services\Shipping;

class ShippingManager
{
    public function __construct(
        protected CourierManager $courierManager
    ) {}

    public function courier(string $courier): CourierInterface
    {
        return $this->courierManager->driver($courier);
    }
}
