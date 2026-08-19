<?php

namespace App\Http\Controllers\Storefront;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentAssistanceRequest;
use App\Models\PaymentAssistanceRequest;
use App\Services\Storefront\PaymentMethodAvailabilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PaymentAssistanceController extends Controller
{
    /**
     * Display the payment assistance form.
     */
    public function create(
        Request $request,
        PaymentMethodAvailabilityService $paymentMethodAvailabilityService
    ): View|RedirectResponse {
        $cart = $request->session()->get('cart', []);

        /*
        |--------------------------------------------------------------------------
        | Cart Check
        |--------------------------------------------------------------------------
        */

        if (empty($cart)) {
            return redirect()
                ->route('storefront.cart.index')
                ->withErrors([
                    'cart' => 'Your cart is empty.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Determine Payment Availability
        |--------------------------------------------------------------------------
        */

        $paymentOptions =
            $paymentMethodAvailabilityService
                ->getCheckoutOptions($cart);

        /*
        |--------------------------------------------------------------------------
        | Safety Check
        |--------------------------------------------------------------------------
        |
        | The assistance page should only be accessible when normal
        | payment is unavailable.
        |
        */

        if (! $paymentOptions['assistance']['required']) {
            return redirect()
                ->route('storefront.checkout.index');
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Cart Subtotal
        |--------------------------------------------------------------------------
        */

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view(
            'storefront.payment-assistance.create',
            compact(
                'cart',
                'subtotal',
                'paymentOptions'
            )
        );
    }

    /**
     * Store a payment assistance request.
     */
    public function store(
        StorePaymentAssistanceRequest $request,
        PaymentMethodAvailabilityService $paymentMethodAvailabilityService
    ): RedirectResponse {
        $cart = $request->session()->get('cart', []);

        /*
        |--------------------------------------------------------------------------
        | Cart Check
        |--------------------------------------------------------------------------
        */

        if (empty($cart)) {
            return redirect()
                ->route('storefront.cart.index')
                ->withErrors([
                    'cart' => 'Your cart is empty.',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Re-check Payment Availability
        |--------------------------------------------------------------------------
        |
        | Never trust the state that was shown on the frontend.
        | Re-check it on the server.
        |
        */

        $paymentOptions =
            $paymentMethodAvailabilityService
                ->getCheckoutOptions($cart);

        if (! $paymentOptions['assistance']['required']) {
            return redirect()
                ->route('storefront.checkout.index')
                ->with('success', 'Your cart can now proceed through checkout.');
        }

        /*
        |--------------------------------------------------------------------------
        | Calculate Subtotal
        |--------------------------------------------------------------------------
        */

        $subtotal = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        /*
        |--------------------------------------------------------------------------
        | Generate Unique Reference
        |--------------------------------------------------------------------------
        */

        do {
            $reference =
                'PAY-'.
                now()->format('Ymd').
                '-'.
                strtoupper(Str::random(6));

        } while (
            PaymentAssistanceRequest::where(
                'reference',
                $reference
            )->exists()
        );

        /*
        |--------------------------------------------------------------------------
        | Create Assistance Request
        |--------------------------------------------------------------------------
        */

        $assistanceRequest = PaymentAssistanceRequest::create([
            'reference' => $reference,

            'customer_name' => $request->validated('customer_name'),

            'customer_email' => $request->validated('customer_email'),

            'customer_phone' => $request->validated('customer_phone'),

            'cart' => $cart,

            'subtotal' => $subtotal,

            'reason' => $paymentOptions['assistance']['reason']
                ?? 'payment_assistance_required',

            'status' => 'pending',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Preserve Cart
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        |
        | We deliberately do NOT clear the cart here.
        |
        | The customer may need to continue the purchase after
        | communicating with the admin.
        |
        */

        return redirect()
            ->route(
                'storefront.payment-assistance.success',
                $assistanceRequest
            );
    }

    /**
     * Display payment assistance confirmation.
     */
    public function success(
        PaymentAssistanceRequest $paymentAssistanceRequest
    ): View {
        return view(
            'storefront.payment-assistance.success',
            compact('paymentAssistanceRequest')
        );
    }
}
