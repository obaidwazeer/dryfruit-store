<?php

namespace App\Services\Storefront;

use App\Models\ShippingRate;

class ShippingRateService
{
    /**
     * Get shipping fee for a city.
     */
    public function getFee(string $city): ?float
    {
        $shippingRate = ShippingRate::query()
            ->where('city', $city)
            ->where('is_active', true)
            ->first();

        return $shippingRate
            ? (float) $shippingRate->rate
            : null;
    }

    /**
     * Determine whether shipping is available for a city.
     */
    public function isAvailable(string $city): bool
    {
        return $this->getFee($city) !== null;
    }
}
