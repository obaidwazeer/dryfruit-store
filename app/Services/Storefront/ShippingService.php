<?php

namespace App\Services\Storefront;

use App\Models\ShippingRate;

class ShippingService
{
    /**
     * Determine whether shipping is available for a city.
     */
    public function isAvailable(string $city): bool
    {
        return ShippingRate::query()
            ->where('city', $city)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Calculate shipping cost for a city.
     */
    public function calculate(string $city): float
    {
        $shippingRate = ShippingRate::query()
            ->where('city', $city)
            ->where('is_active', true)
            ->first();

        if (! $shippingRate) {
            throw new \RuntimeException(
                "Shipping is currently unavailable for {$city}."
            );
        }

        return (float) $shippingRate->rate;
    }
}
