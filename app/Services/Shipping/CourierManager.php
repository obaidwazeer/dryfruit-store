<?php

namespace App\Services\Shipping;

use InvalidArgumentException;

class CourierManager
{
    public function driver(string $courier): CourierInterface
    {
        return match ($courier) {

            'leopards' => app(
                LeopardsCourierService::class
            ),

            default => throw new InvalidArgumentException(
                "Unsupported courier [{$courier}]."
            ),

        };
    }
}
