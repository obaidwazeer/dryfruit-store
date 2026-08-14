<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function index(Request $request): View
    {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return view('storefront.cart.index')
                // ->route('storefront.cart.index')
                ->withErrors([
                    'cart' => 'Your cart is empty.',
                ]);
        }

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('storefront.checkout.index', compact(
            'cart',
            'subtotal'
        ));
    }

    public function store(
        StoreCheckoutRequest $request
    ): RedirectResponse {
        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('storefront.shop')
                ->withErrors([
                    'cart' => 'Your cart is empty.',
                ]);
        }

        $order = DB::transaction(function () use (
            $request,
            $cart
        ) {
            $subtotal = 0;

            $validatedItems = [];

            foreach ($cart as $cartItem) {

                $variant = ProductVariant::query()
                    ->where('id', $cartItem['variant_id'])
                    ->where('is_active', true)
                    ->whereHas('product', function ($query) {
                        $query->where('status', 'active');
                    })
                    ->with('product')
                    ->lockForUpdate()
                    ->first();

                if (! $variant) {
                    throw new \RuntimeException(
                        'One of the products in your cart is no longer available.'
                    );
                }

                $quantity = (int) $cartItem['quantity'];

                if ($quantity > $variant->stock_quantity) {
                    throw new \RuntimeException(
                        "Insufficient stock for {$variant->name}."
                    );
                }

                $unitPrice = (float) $variant->price;

                $itemTotal = $unitPrice * $quantity;

                $subtotal += $itemTotal;

                $validatedItems[] = [
                    'variant' => $variant,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'total' => $itemTotal,
                ];
            }

            $shippingCost = 0;

            $discount = 0;

            $total = $subtotal
                + $shippingCost
                - $discount;

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),

                'status' => 'pending',

                'payment_status' => 'pending',

                'payment_method' => $request->validated(
                    'payment_method'
                ),

                'subtotal' => $subtotal,

                'discount' => $discount,

                'shipping_fee' => $shippingCost,

                'total' => $total,

                'customer_name' => $request->validated(
                    'customer_name'
                ),

                'customer_email' => $request->validated(
                    'customer_email'
                ),

                'customer_phone' => $request->validated(
                    'customer_phone'
                ),

                'shipping_address' => $request->validated(
                    'shipping_address'
                ),

                'shipping_city' => $request->validated(
                    'shipping_city'
                ),

                'shipping_postal_code' => $request->validated(
                    'shipping_postal_code'
                ),

                'customer_notes' => $request->validated(
                    'customer_notes'
                ),
            ]);

            foreach ($validatedItems as $item) {

                $variant = $item['variant'];

                $order->items()->create([
                    'product_id' => $variant->product_id,
                    'product_variant_id' => $variant->id,

                    'product_name' => $variant->product->name,
                    'variant_name' => $variant->name,

                    'sku' => $variant->sku,

                    'weight_grams' => $variant->weight_grams,
                    'unit_price' => $variant->price,
                    'quantity' => $item['quantity'],
                    'total' => $item['total'],
                ]);

                $variant->decrement(
                    'stock_quantity',
                    $item['quantity']
                );
            }

            return $order;
        });

        $request->session()->forget('cart');

        $request->session()->put(
            'last_order_id',
            $order->id
        );

        return redirect()
            ->route(
                'storefront.checkout.success',
                $order
            );
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'DF-'.now()->format('Ymd').'-'.
                strtoupper(Str::random(6));
        } while (
            Order::where('order_number', $number)->exists()
        );

        return $number;
    }

    public function success(Order $order): View
    {
        abort_unless(
            session('last_order_id') === $order->id,
            404
        );

        return view(
            'storefront.checkout.success',
            compact('order')
        );
    }
}
