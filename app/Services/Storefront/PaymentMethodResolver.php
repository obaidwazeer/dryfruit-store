<?php

namespace App\Services\Storefront;

use App\Models\ProductVariant;
use RuntimeException;

class PaymentMethodResolver
{
    /**
     * Determine which payment methods are available
     * for the complete cart.
     */
    public function resolve(array $cart): array
    {
        if (empty($cart)) {
            return [
                'cod' => false,
                'online' => false,
            ];
        }

        $allowCod = true;
        $allowOnline = true;

        foreach ($cart as $cartItem) {

            $variant = ProductVariant::query()
                ->where('id', $cartItem['variant_id'])
                ->where('is_active', true)
                ->whereHas('product', function ($query) {
                    $query->where('status', 'active');
                })
                ->with('product')
                ->first();

            if (! $variant) {
                throw new RuntimeException(
                    'One of the products in your cart is no longer available.'
                );
            }

            /*
             * Every product in the cart must allow the
             * selected payment method.
             */
            if (! $variant->product->allow_cod) {
                $allowCod = false;
            }

            if (! $variant->product->allow_online_payment) {
                $allowOnline = false;
            }
        }

        return [
            'cod' => $allowCod,
            'online' => $allowOnline,
        ];
    }
}
