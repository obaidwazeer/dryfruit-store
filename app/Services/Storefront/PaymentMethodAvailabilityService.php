<?php

namespace App\Services\Storefront;

use App\Models\ProductVariant;
use Illuminate\Support\Collection;

class PaymentMethodAvailabilityService
{
    /**
     * Determine which payment methods are available
     * for all products currently in the cart.
     */
    public function determine(array $cart): array
    {
        if (empty($cart)) {
            return [
                'cod' => false,
                'online' => false,
                'assistance_required' => false,
                'reason' => 'empty_cart',
            ];
        }

        $variants = $this->loadVariants($cart);

        /*
        |--------------------------------------------------------------------------
        | Validate Cart Products
        |--------------------------------------------------------------------------
        |
        | Every cart variant must still exist and be active.
        |
        */

        $cartVariantIds = collect($cart)
            ->pluck('variant_id')
            ->filter()
            ->unique()
            ->values();

        $loadedVariantIds = $variants
            ->pluck('id')
            ->unique()
            ->values();

        if (
            $cartVariantIds->count() !== $loadedVariantIds->count()
            ||
            $cartVariantIds->diff($loadedVariantIds)->isNotEmpty()
        ) {
            return [
                'cod' => false,
                'online' => false,
                'assistance_required' => true,
                'reason' => 'products_unavailable',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Determine Common Payment Methods
        |--------------------------------------------------------------------------
        |
        | A payment method can be offered only when EVERY product
        | in the cart supports that payment method.
        |
        */

        $codAvailable = $variants->every(function ($variant) {
            return (bool) $variant->product->allow_cod;
        });

        $onlineAvailable = $variants->every(function ($variant) {
            return (bool) $variant->product->allow_online_payment;
        });

        /*
        |--------------------------------------------------------------------------
        | Determine Individual Product Availability
        |--------------------------------------------------------------------------
        |
        | Used to distinguish:
        |
        | 1. Mixed payment methods
        | 2. Products with no payment method
        |
        */

        $hasAnyPaymentMethod = $variants->contains(function ($variant) {
            return
                (bool) $variant->product->allow_cod ||
                (bool) $variant->product->allow_online_payment;
        });

        /*
        |--------------------------------------------------------------------------
        | Common Payment Method Available
        |--------------------------------------------------------------------------
        */

        if ($codAvailable || $onlineAvailable) {
            return [
                'cod' => $codAvailable,
                'online' => $onlineAvailable,
                'assistance_required' => false,
                'reason' => null,
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | No Common Payment Method
        |--------------------------------------------------------------------------
        |
        | At this point:
        |
        | COD = unavailable for the entire cart
        | Online = unavailable for the entire cart
        |
        */

        if ($hasAnyPaymentMethod) {

            /*
            |--------------------------------------------------------------------------
            | Mixed Payment Methods
            |--------------------------------------------------------------------------
            |
            | Example:
            |
            | Product A -> COD only
            | Product B -> Online only
            |
            */

            return [
                'cod' => false,
                'online' => false,
                'assistance_required' => true,
                'reason' => 'mixed_payment_methods',
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | No Payment Method Available
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | Product A -> COD disabled
        | Product A -> Online disabled
        |
        */

        return [
            'cod' => false,
            'online' => false,
            'assistance_required' => true,
            'reason' => 'no_payment_method',
        ];
    }

    /**
     * Get payment options for the checkout page.
     */
    public function getCheckoutOptions(array $cart): array
    {
        $availability = $this->determine($cart);

        return [
            'cod' => [
                'available' => $availability['cod'],
                'label' => 'Cash on Delivery',
            ],

            'online' => [
                'available' => $availability['online'],
                'label' => 'Online Payment',
            ],

            'assistance' => [
                'required' => $availability['assistance_required'],
                'reason' => $availability['reason'],
            ],
        ];
    }

    /**
     * Load active variants from the cart.
     */
    private function loadVariants(array $cart): Collection
    {
        $variantIds = collect($cart)
            ->pluck('variant_id')
            ->filter()
            ->unique()
            ->values();

        if ($variantIds->isEmpty()) {
            return collect();
        }

        return ProductVariant::query()
            ->whereIn('id', $variantIds->all())
            ->where('is_active', true)
            ->with('product')
            ->get();
    }
}
