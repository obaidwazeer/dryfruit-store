@extends('layouts.storefront.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')

    <div class="container py-5">

        {{-- =========================================================
            Validation Errors
        ========================================================== --}}

        @if ($errors->any())

            <div class="alert alert-danger mb-4">

                <strong>
                    Please correct the following:
                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)
                        <li>
                            {{ $error }}
                        </li>
                    @endforeach

                </ul>

            </div>

        @endif


        {{-- =========================================================
            Checkout Header
        ========================================================== --}}

        <div class="mb-5">

            <h1 class="fw-bold">
                Checkout
            </h1>

            <p class="text-muted mb-0">
                Complete your details to place your order.
            </p>

        </div>


        {{-- =========================================================
            Checkout Form
        ========================================================== --}}

        <form method="POST" action="{{ route('storefront.checkout.store') }}">

            @csrf

            <div class="row g-4">

                {{-- =================================================
                    Customer Information
                ================================================== --}}

                <div class="col-lg-7">

                    <div class="border rounded p-4">

                        <h4 class="mb-4">
                            Delivery Information
                        </h4>


                        {{-- Full Name --}}

                        <div class="mb-3">

                            <label for="customer_name" class="form-label">
                                Full Name
                            </label>

                            <input
                                type="text"
                                id="customer_name"
                                name="customer_name"
                                class="form-control @error('customer_name') is-invalid @enderror"
                                value="{{ old('customer_name') }}"
                                placeholder="Enter your full name"
                                required
                            >

                            @error('customer_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Phone --}}

                        <div class="mb-3">

                            <label for="customer_phone" class="form-label">
                                Phone Number
                            </label>

                            <input
                                type="text"
                                id="customer_phone"
                                name="customer_phone"
                                class="form-control @error('customer_phone') is-invalid @enderror"
                                value="{{ old('customer_phone') }}"
                                placeholder="03XXXXXXXXX"
                                required
                            >

                            @error('customer_phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Email --}}

                        <div class="mb-3">

                            <label for="customer_email" class="form-label">

                                Email Address

                                <span class="text-muted">
                                    (Optional)
                                </span>

                            </label>

                            <input
                                type="email"
                                id="customer_email"
                                name="customer_email"
                                class="form-control @error('customer_email') is-invalid @enderror"
                                value="{{ old('customer_email') }}"
                                placeholder="you@example.com"
                            >

                            @error('customer_email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- City --}}

                        <div class="mb-3">

                            <label for="shipping_city" class="form-label">
                                City
                            </label>

                            <select
                                id="shipping_city"
                                name="shipping_city"
                                class="form-select @error('shipping_city') is-invalid @enderror"
                                required
                            >

                                <option value="">
                                    Select City
                                </option>

                                <option
                                    value="Islamabad"
                                    @selected(old('shipping_city') === 'Islamabad')
                                >
                                    Islamabad
                                </option>

                                <option
                                    value="Rawalpindi"
                                    @selected(old('shipping_city') === 'Rawalpindi')
                                >
                                    Rawalpindi
                                </option>

                                <option
                                    value="Lahore"
                                    @selected(old('shipping_city') === 'Lahore')
                                >
                                    Lahore
                                </option>

                                <option
                                    value="Karachi"
                                    @selected(old('shipping_city') === 'Karachi')
                                >
                                    Karachi
                                </option>

                                <option
                                    value="Peshawar"
                                    @selected(old('shipping_city') === 'Peshawar')
                                >
                                    Peshawar
                                </option>

                                <option
                                    value="Quetta"
                                    @selected(old('shipping_city') === 'Quetta')
                                >
                                    Quetta
                                </option>

                            </select>

                            @error('shipping_city')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Postcode --}}

                        <div class="mb-3">

                            <label for="shipping_postal_code" class="form-label">

                                Postal Code

                                <span class="text-muted">
                                    (Optional)
                                </span>

                            </label>

                            <input
                                type="text"
                                id="shipping_postal_code"
                                name="shipping_postal_code"
                                class="form-control"
                                value="{{ old('shipping_postal_code') }}"
                                placeholder="e.g. 44000"
                            >

                        </div>


                        {{-- Address --}}

                        <div class="mb-3">

                            <label for="shipping_address" class="form-label">
                                Complete Address
                            </label>

                            <textarea
                                id="shipping_address"
                                name="shipping_address"
                                rows="4"
                                class="form-control @error('shipping_address') is-invalid @enderror"
                                placeholder="House number, street, area, landmark..."
                                required
                            >{{ old('shipping_address') }}</textarea>

                            @error('shipping_address')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Notes --}}

                        <div class="mb-3">

                            <label for="customer_notes" class="form-label">

                                Order Notes

                                <span class="text-muted">
                                    (Optional)
                                </span>

                            </label>

                            <textarea
                                id="customer_notes"
                                name="customer_notes"
                                rows="3"
                                class="form-control"
                                placeholder="Any special instructions?"
                            >{{ old('customer_notes') }}</textarea>

                        </div>


                        {{-- =================================================
                            Payment Method
                        ================================================== --}}

                        <div class="checkout-section mt-5">

                            <div class="checkout-section-heading">

                                <span class="storefront-section-eyebrow">
                                    Payment
                                </span>

                                <h2>
                                    Choose Payment Method
                                </h2>

                                <p>
                                    Select your preferred payment method for this order.
                                </p>

                            </div>


                            {{-- Payment Assistance --}}

                            @if ($paymentOptions['assistance']['required'])

                                <div class="checkout-payment-assistance">

                                    <div class="checkout-payment-assistance-icon">

                                        <i class="bi bi-headset"></i>

                                    </div>

                                    <div>

                                        <h5>
                                            Payment Assistance Required
                                        </h5>

                                        <p class="mb-3">

                                            Some products in your cart cannot
                                            be purchased using the same payment
                                            method.

                                        </p>

                                        <a
                                            href="{{ route('storefront.payment-assistance.create') }}"
                                            class="btn btn-storefront-primary"
                                        >

                                            <i class="bi bi-chat-dots me-2"></i>

                                            Contact Us for Payment Assistance

                                        </a>

                                    </div>

                                </div>

                            @else

                                @php
                                    $defaultPaymentMethod = old(
                                        'payment_method',
                                        $paymentOptions['cod']['available']
                                            ? 'cod'
                                            : ($paymentOptions['online']['available'] ? 'online' : null)
                                    );

                                    $oldPaymentProvider = old('payment_provider');
                                @endphp


                                {{-- =================================================
                                    Payment Methods
                                ================================================== --}}

                                <div class="checkout-payment-methods">

                                    {{-- COD --}}

                                    @if ($paymentOptions['cod']['available'])

                                        <label class="checkout-payment-option">

                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="cod"
                                                @checked($defaultPaymentMethod === 'cod')
                                            >

                                            <span class="checkout-payment-option-content">

                                                <span class="checkout-payment-option-icon">

                                                    <i class="bi bi-cash-stack"></i>

                                                </span>

                                                <span>

                                                    <strong>
                                                        Cash on Delivery
                                                    </strong>

                                                    <small>
                                                        Pay when your order is delivered.
                                                    </small>

                                                </span>

                                            </span>

                                        </label>

                                    @endif


                                    {{-- Online Payment --}}

                                    @if ($paymentOptions['online']['available'])

                                        <label class="checkout-payment-option">

                                            <input
                                                type="radio"
                                                name="payment_method"
                                                value="online"
                                                @checked($defaultPaymentMethod === 'online')
                                            >

                                            <span class="checkout-payment-option-content">

                                                <span class="checkout-payment-option-icon">

                                                    <i class="bi bi-credit-card"></i>

                                                </span>

                                                <span>

                                                    <strong>
                                                        Online Payment
                                                    </strong>

                                                    <small>
                                                        Pay using Easypaisa, JazzCash or Bank Transfer.
                                                    </small>

                                                </span>

                                            </span>

                                        </label>

                                    @endif

                                </div>


                                {{-- =================================================
                                    Online Payment Providers
                                ================================================== --}}

                                @if ($paymentOptions['online']['available'])

                                    <div
                                        id="online-payment-providers"
                                        class="mt-3 p-3 border rounded"
                                        style="{{ $defaultPaymentMethod === 'online' ? '' : 'display: none;' }}"
                                    >

                                        <h6 class="mb-3">
                                            Select Online Payment Method
                                        </h6>


                                        {{-- Easypaisa --}}

                                        <div class="form-check mb-2">

                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="payment_provider"
                                                id="payment_provider_easypaisa"
                                                value="easypaisa"
                                                @checked($oldPaymentProvider === 'easypaisa')
                                            >

                                            <label
                                                class="form-check-label"
                                                for="payment_provider_easypaisa"
                                            >
                                                Easypaisa
                                            </label>

                                        </div>


                                        {{-- JazzCash --}}

                                        <div class="form-check mb-2">

                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="payment_provider"
                                                id="payment_provider_jazzcash"
                                                value="jazzcash"
                                                @checked($oldPaymentProvider === 'jazzcash')
                                            >

                                            <label
                                                class="form-check-label"
                                                for="payment_provider_jazzcash"
                                            >
                                                JazzCash
                                            </label>

                                        </div>


                                        {{-- Bank Transfer --}}

                                        <div class="form-check">

                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                name="payment_provider"
                                                id="payment_provider_bank_transfer"
                                                value="bank_transfer"
                                                @checked($oldPaymentProvider === 'bank_transfer')
                                            >

                                            <label
                                                class="form-check-label"
                                                for="payment_provider_bank_transfer"
                                            >
                                                Bank Transfer
                                            </label>

                                        </div>


                                        @error('payment_provider')

                                            <div class="text-danger small mt-2">
                                                {{ $message }}
                                            </div>

                                        @enderror


                                        <div class="alert alert-info mt-3 mb-0">

                                            <small>
                                                Online payment instructions will be provided after the payment system is implemented.
                                            </small>

                                        </div>

                                    </div>

                                @endif

                            @endif

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    Order Summary
                ================================================== --}}

                <div class="col-lg-5">

                    <div class="border rounded p-4">

                        <h4 class="mb-4">
                            Order Summary
                        </h4>


                        {{-- Cart Items --}}

                        @foreach ($cart as $item)

                            @php
                                $itemTotal = $item['price'] * $item['quantity'];
                            @endphp

                            <div class="d-flex justify-content-between mb-3">

                                <div>

                                    <strong>
                                        {{ $item['name'] }}
                                    </strong>

                                    <div class="small text-muted">

                                        {{ $item['variant_name'] }}

                                        ×

                                        {{ $item['quantity'] }}

                                    </div>

                                </div>

                                <span>
                                    Rs.
                                    {{ number_format($itemTotal, 2) }}
                                </span>

                            </div>

                        @endforeach


                        <hr>


                        {{-- Subtotal --}}

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Subtotal
                            </span>

                            <strong>
                                Rs.
                                {{ number_format($subtotal, 2) }}
                            </strong>

                        </div>


                        {{-- Shipping --}}

                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Shipping
                            </span>

                            @if ($shippingAvailable)

                                <strong id="shipping-fee">
                                    Rs.
                                    {{ number_format($shippingCost, 2) }}
                                </strong>

                            @else

                                <span id="shipping-fee" class="text-muted">
                                    Select city
                                </span>

                            @endif

                        </div>


                        <hr>


                        {{-- Total --}}

                        <div class="d-flex justify-content-between">

                            <strong>
                                Total
                            </strong>

                            <strong id="checkout-total" class="fs-5">
                                Rs.
                                {{ number_format($total, 2) }}
                            </strong>

                        </div>


                        {{-- Place Order --}}

                        <button
                            type="submit"
                            id="place-order-button"
                            class="btn btn-storefront-primary w-100 mt-4"
                            @disabled(
                                (
                                    !$paymentOptions['cod']['available']
                                    && !$paymentOptions['online']['available']
                                )
                                || !$shippingAvailable
                            )
                        >
                            Place Order
                        </button>


                        {{-- Back To Cart --}}

                        <a
                            href="{{ route('storefront.cart.index') }}"
                            class="btn btn-outline-secondary w-100 mt-2"
                        >
                            Back to Cart
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

@endsection


@push('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const citySelect =
                document.getElementById('shipping_city');

            const shippingFeeElement =
                document.getElementById('shipping-fee');

            const checkoutTotalElement =
                document.getElementById('checkout-total');

            const placeOrderButton =
                document.getElementById('place-order-button');

            const onlinePaymentProviders =
                document.getElementById('online-payment-providers');

            const paymentMethodInputs =
                document.querySelectorAll(
                    'input[name="payment_method"]'
                );

            const paymentProviderInputs =
                document.querySelectorAll(
                    'input[name="payment_provider"]'
                );

            const subtotal =
                Number({{ $subtotal }});

            const paymentAvailable =
                @json(
                    $paymentOptions['cod']['available']
                    || $paymentOptions['online']['available']
                );


            if (
                !citySelect ||
                !shippingFeeElement ||
                !checkoutTotalElement ||
                !placeOrderButton
            ) {
                return;
            }


            /*
            |--------------------------------------------------------------------------
            | Get Selected Payment Method
            |--------------------------------------------------------------------------
            */

            function getSelectedPaymentMethod() {

                const selected =
                    document.querySelector(
                        'input[name="payment_method"]:checked'
                    );

                return selected
                    ? selected.value
                    : null;
            }


            /*
            |--------------------------------------------------------------------------
            | Get Selected Payment Provider
            |--------------------------------------------------------------------------
            */

            function getSelectedPaymentProvider() {

                const selected =
                    document.querySelector(
                        'input[name="payment_provider"]:checked'
                    );

                return selected
                    ? selected.value
                    : null;
            }


            /*
            |--------------------------------------------------------------------------
            | Update Online Payment Providers
            |--------------------------------------------------------------------------
            */

            function updatePaymentProviderVisibility() {

                const paymentMethod =
                    getSelectedPaymentMethod();


                if (!onlinePaymentProviders) {
                    return;
                }


                if (paymentMethod === 'online') {

                    onlinePaymentProviders.style.display =
                        'block';

                } else {

                    onlinePaymentProviders.style.display =
                        'none';

                    paymentProviderInputs.forEach(function (input) {

                        input.checked = false;

                    });

                }


                updatePlaceOrderButton();
            }


            /*
            |--------------------------------------------------------------------------
            | Update Place Order Button
            |--------------------------------------------------------------------------
            */

            function updatePlaceOrderButton() {

                if (!paymentAvailable) {

                    placeOrderButton.disabled = true;

                    return;
                }


                const paymentMethod =
                    getSelectedPaymentMethod();


                if (!paymentMethod) {

                    placeOrderButton.disabled = true;

                    return;
                }


                if (paymentMethod === 'online') {

                    const paymentProvider =
                        getSelectedPaymentProvider();

                    placeOrderButton.disabled =
                        !paymentProvider;

                    return;
                }


                placeOrderButton.disabled = false;
            }


            /*
            |--------------------------------------------------------------------------
            | Payment Method Change
            |--------------------------------------------------------------------------
            */

            paymentMethodInputs.forEach(function (input) {

                input.addEventListener(
                    'change',
                    function () {

                        updatePaymentProviderVisibility();

                    }
                );

            });


            /*
            |--------------------------------------------------------------------------
            | Payment Provider Change
            |--------------------------------------------------------------------------
            */

            paymentProviderInputs.forEach(function (input) {

                input.addEventListener(
                    'change',
                    function () {

                        updatePlaceOrderButton();

                    }
                );

            });


            /*
            |--------------------------------------------------------------------------
            | Initial Payment State
            |--------------------------------------------------------------------------
            */

            updatePaymentProviderVisibility();


            /*
            |--------------------------------------------------------------------------
            | City Change
            |--------------------------------------------------------------------------
            */

            citySelect.addEventListener(
                'change',
                async function () {

                    const city = this.value;


                    if (!city) {

                        shippingFeeElement.textContent =
                            'Select city';

                        checkoutTotalElement.textContent =
                            'Rs. ' +
                            subtotal.toFixed(2);

                        updatePlaceOrderButton();

                        return;
                    }


                    shippingFeeElement.textContent =
                        'Calculating...';

                    placeOrderButton.disabled = true;


                    try {

                        const response = await fetch(
                            '{{ route('storefront.checkout.shipping') }}?city=' +
                            encodeURIComponent(city),
                            {
                                headers: {
                                    'Accept': 'application/json'
                                }
                            }
                        );


                        if (!response.ok) {

                            throw new Error(
                                'Unable to calculate shipping.'
                            );

                        }


                        const data =
                            await response.json();


                        const shippingFee =
                            Number(data.shipping_fee);


                        if (Number.isNaN(shippingFee)) {

                            throw new Error(
                                'Invalid shipping fee.'
                            );

                        }


                        const total =
                            subtotal + shippingFee;


                        shippingFeeElement.textContent =
                            'Rs. ' +
                            shippingFee.toFixed(2);


                        checkoutTotalElement.textContent =
                            'Rs. ' +
                            total.toFixed(2);


                        updatePlaceOrderButton();


                    } catch (error) {

                        console.error(error);


                        shippingFeeElement.textContent =
                            'Unable to calculate';


                        checkoutTotalElement.textContent =
                            'Rs. ' +
                            subtotal.toFixed(2);


                        placeOrderButton.disabled =
                            true;

                    }

                }
            );

        });
    </script>

@endpush
