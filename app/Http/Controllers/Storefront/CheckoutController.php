<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCheckoutRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Models\ProductVariant;
use App\Services\Payments\PaymentTransactionService;
use App\Services\Storefront\PaymentMethodAvailabilityService;
use App\Services\Storefront\PaymentMethodResolver;
use App\Services\Storefront\ShippingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Checkout Page
    |--------------------------------------------------------------------------
    */

    public function index(
        Request $request,
        PaymentMethodResolver $paymentMethodResolver,
        PaymentMethodAvailabilityService $paymentMethodAvailabilityService,
        ShippingService $shippingService
    ): View|RedirectResponse {

        $cart = $request->session()->get('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('storefront.cart.index')
                ->withErrors([
                    'cart' => 'Your cart is empty.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Cart Subtotal
        |--------------------------------------------------------------------------
        */

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        /*
        |--------------------------------------------------------------------------
        | Payment Methods
        |--------------------------------------------------------------------------
        */

        $paymentMethods = $paymentMethodResolver->resolve($cart);

        $paymentOptions = $paymentMethodAvailabilityService
            ->getCheckoutOptions($cart);

        /*
        |--------------------------------------------------------------------------
        | Shipping
        |--------------------------------------------------------------------------
        */

        $selectedCity = old('shipping_city');

        $shippingAvailable = false;

        $shippingCost = 0;

        if ($selectedCity) {

            $shippingAvailable = $shippingService
                ->isAvailable($selectedCity);

            if ($shippingAvailable) {

                $shippingCost = $shippingService
                    ->calculate($selectedCity);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Order Total
        |--------------------------------------------------------------------------
        */

        $total = $subtotal + $shippingCost;

        return view(
            'storefront.checkout.index',
            compact(
                'cart',
                'subtotal',
                'shippingCost',
                'shippingAvailable',
                'total',
                'paymentMethods',
                'paymentOptions'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Store Checkout
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreCheckoutRequest $request,
        PaymentMethodAvailabilityService $paymentMethodAvailabilityService,
        ShippingService $shippingService,
        PaymentTransactionService $paymentTransactionService
    ): RedirectResponse {

        $validated = $request->validated();

        $cart = $request->session()->get('cart', []);

        /*
        |--------------------------------------------------------------------------
        | Cart Validation
        |--------------------------------------------------------------------------
        */

        if (empty($cart)) {

            return redirect()
                ->route('storefront.shop')
                ->withErrors([
                    'cart' => 'Your cart is empty.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Method Availability
        |--------------------------------------------------------------------------
        */

        $paymentOptions = $paymentMethodAvailabilityService
            ->getCheckoutOptions($cart);

        $paymentMethod = $validated['payment_method'];

        /*
        |--------------------------------------------------------------------------
        | Validate COD Availability
        |--------------------------------------------------------------------------
        */

        if (
            $paymentMethod === 'cod' &&
            ! $paymentOptions['cod']['available']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'payment_method' => 'Cash on Delivery is not available for the products in your cart.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate Online Payment Availability
        |--------------------------------------------------------------------------
        */

        if (
            $paymentMethod === 'online' &&
            ! $paymentOptions['online']['available']
        ) {

            return back()
                ->withInput()
                ->withErrors([
                    'payment_method' => 'Online payment is not available for the products in your cart.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Payment Provider
        |--------------------------------------------------------------------------
        */

        $paymentProvider = null;

        if ($paymentMethod === 'online') {

            $paymentProvider = $validated['payment_provider'] ?? null;

            if (! $paymentProvider) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'payment_provider' => 'Please select an online payment method.',
                    ]);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Shipping Validation
        |--------------------------------------------------------------------------
        */

        $shippingCity = $validated['shipping_city'];

        if (! $shippingService->isAvailable($shippingCity)) {

            return back()
                ->withInput()
                ->withErrors([
                    'shipping_city' => 'Shipping is currently unavailable for the selected city.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Shipping
        |--------------------------------------------------------------------------
        */

        $shippingCost = $shippingService->calculate($shippingCity);

        /*
        |--------------------------------------------------------------------------
        | Create Customer + Order
        |--------------------------------------------------------------------------
        */

        $order = DB::transaction(function () use (
            $validated,
            $cart,
            $shippingCost,
            $paymentMethod,
            $paymentProvider
        ) {

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            $customer = Customer::updateOrCreate(
                [
                    'phone' => $validated['customer_phone'],
                ],
                [
                    'name' => $validated['customer_name'],
                    'email' => $validated['customer_email'] ?? null,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Validate Cart Items
            |--------------------------------------------------------------------------
            */

            $subtotal = 0;

            $validatedItems = [];

            foreach ($cart as $cartItem) {

                $variant = ProductVariant::query()
                    ->where(
                        'id',
                        $cartItem['variant_id']
                    )
                    ->where(
                        'is_active',
                        true
                    )
                    ->whereHas(
                        'product',
                        function ($query) {
                            $query->where(
                                'status',
                                'active'
                            );
                        }
                    )
                    ->with('product')
                    ->lockForUpdate()
                    ->first();

                if (! $variant) {

                    throw new \RuntimeException(
                        'One of the products in your cart is no longer available.'
                    );
                }

                $quantity = (int) $cartItem['quantity'];

                if ($quantity <= 0) {

                    throw new \RuntimeException(
                        "Invalid quantity for {$variant->name}."
                    );
                }

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

            /*
            |--------------------------------------------------------------------------
            | Discount
            |--------------------------------------------------------------------------
            */

            $discount = 0;

            /*
            |--------------------------------------------------------------------------
            | Final Total
            |--------------------------------------------------------------------------
            */

            $total = $subtotal
                + $shippingCost
                - $discount;

            /*
            |--------------------------------------------------------------------------
            | Create Order
            |--------------------------------------------------------------------------
            */

            $order = Order::create([

                'customer_id' => $customer->id,

                'order_number' => $this->generateOrderNumber(),

                'status' => Order::STATUS_PENDING,

                'payment_status' => Order::PAYMENT_PENDING,

                'payment_method' => $paymentMethod,

                'payment_provider' => $paymentProvider,

                'subtotal' => $subtotal,

                'discount' => $discount,

                'shipping_fee' => $shippingCost,

                'total' => $total,

                /*
                |--------------------------------------------------------------------------
                | Customer Snapshot
                |--------------------------------------------------------------------------
                */

                'customer_name' => $validated['customer_name'],

                'customer_email' => $validated['customer_email'] ?? null,

                'customer_phone' => $validated['customer_phone'],

                'shipping_address' => $validated['shipping_address'],

                'shipping_city' => $validated['shipping_city'],

                'shipping_postal_code' => $validated['shipping_postal_code'] ?? null,

                'customer_notes' => $validated['customer_notes'] ?? null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create Order Items + Reduce Stock
            |--------------------------------------------------------------------------
            */

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

        /*
        |--------------------------------------------------------------------------
        | Create Payment Transaction
        |--------------------------------------------------------------------------
        */

        $paymentTransaction = null;

        if ($order->payment_method === 'online') {

            $paymentTransaction =
                $paymentTransactionService->create(
                    $order,
                    $order->payment_provider
                );

            /*
            |--------------------------------------------------------------------------
            | Safety Check
            |--------------------------------------------------------------------------
            */

            if (! $paymentTransaction) {

                throw new \RuntimeException(
                    'Payment transaction could not be created.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Clear Cart
        |--------------------------------------------------------------------------
        */

        $request->session()->forget('cart');

        /*
        |--------------------------------------------------------------------------
        | Store Last Order ID In Session
        |--------------------------------------------------------------------------
        |
        | This is also used by the payment page to ensure that the customer
        | can only access the transaction belonging to the order they just
        | created.
        |
        */

        $request->session()->put(
            'last_order_id',
            $order->id
        );

        /*
        |--------------------------------------------------------------------------
        | Bank Transfer
        |--------------------------------------------------------------------------
        */

        if (
            $order->payment_method === 'online' &&
            $order->payment_provider === 'bank_transfer'
        ) {

            return redirect()->route(
                'storefront.payment.bank-transfer',
                [
                    'transaction' => $paymentTransaction->transaction_reference,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | COD / Other Payment Flow
        |--------------------------------------------------------------------------
        */

        return redirect()->route(
            'storefront.checkout.success',
            $order
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Bank Transfer Page
    |--------------------------------------------------------------------------
    */

    public function bankTransfer(
        PaymentTransaction $transaction
    ): View {

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        |
        | The transaction must belong to the order stored in the
        | current customer's session.
        |
        */

        abort_unless(
            session('last_order_id') === $transaction->order_id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Provider Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $transaction->provider === 'bank_transfer',
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Load Order
        |--------------------------------------------------------------------------
        */

        $transaction->load('order');

        $order = $transaction->order;

        return view(
            'storefront.payment.bank-transfer',
            compact(
                'order',
                'transaction'
            )
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Customer Confirms Bank Transfer
    |--------------------------------------------------------------------------
    */

    public function submitBankTransfer(
        PaymentTransaction $transaction
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | Security
        |--------------------------------------------------------------------------
        */

        abort_unless(
            session('last_order_id') === $transaction->order_id,
            404
        );

        abort_unless(
            $transaction->provider === 'bank_transfer',
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Do Not Allow Already Finalized Transactions
        |--------------------------------------------------------------------------
        */

        if (
            in_array(
                $transaction->status,
                [
                    PaymentTransaction::STATUS_PAID,
                    PaymentTransaction::STATUS_FAILED,
                ],
                true
            )
        ) {

            return back()
                ->withErrors([
                    'payment' => 'This payment transaction has already been finalized.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Mark Transaction as Processing
        |--------------------------------------------------------------------------
        |
        | This DOES NOT mean that the payment is approved.
        |
        */

        $transaction->update([
            'status' => PaymentTransaction::STATUS_PROCESSING,

            'response_message' => 'Customer reported that the bank transfer was completed and payment is awaiting manual verification.',
        ]);

        return redirect()
            ->route(
                'storefront.payment.bank-transfer',
                [
                    'transaction' => $transaction->transaction_reference,
                ]
            )
            ->with(
                'success',
                'Thank you. Your transfer has been submitted for verification. Your payment will remain pending until our team verifies it.'
            );
    }

    /*
    |--------------------------------------------------------------------------
    | Generate Order Number
    |--------------------------------------------------------------------------
    */

    private function generateOrderNumber(): string
    {
        do {

            $number =
                'DF-'
                .now()->format('Ymd')
                .'-'
                .strtoupper(
                    Str::random(6)
                );

        } while (
            Order::where(
                'order_number',
                $number
            )->exists()
        );

        return $number;
    }

    /*
    |--------------------------------------------------------------------------
    | Checkout Success
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Shipping Calculation
    |--------------------------------------------------------------------------
    */

    public function shipping(
        Request $request,
        ShippingService $shippingService
    ): JsonResponse {

        $city =
            $request->query('city');

        if (! $city) {

            return response()->json([
                'message' => 'City is required.',
            ], 422);
        }

        if (
            ! $shippingService->isAvailable($city)
        ) {

            return response()->json([
                'message' => 'Shipping is not available for the selected city.',
            ], 422);
        }

        $shippingFee =
            $shippingService->calculate($city);

        return response()->json([
            'city' => $city,

            'shipping_fee' => $shippingFee,
        ]);
    }
}
