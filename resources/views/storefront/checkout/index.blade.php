@extends('layouts.storefront.app')

@section('title', 'Checkout - ' . config('app.name'))

@section('content')

    <div class="container py-5">

        {{-- Validation Errors --}}
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


        <div class="mb-5">

            <h1 class="fw-bold">
                Checkout
            </h1>

            <p class="text-muted mb-0">
                Complete your details to place your order.
            </p>

        </div>


        {{-- IMPORTANT: The entire checkout must be inside the form --}}
        <form method="POST" action="{{ route('storefront.checkout.store') }}">

            @csrf


            <div class="row g-4">

                {{-- Customer Information --}}
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

                            <input type="text" id="customer_name" name="customer_name" class="form-control"
                                value="{{ old('customer_name') }}" placeholder="Enter your full name" required>

                        </div>


                        {{-- Phone --}}
                        <div class="mb-3">

                            <label for="customer_phone" class="form-label">
                                Phone Number
                            </label>

                            <input type="text" id="customer_phone" name="customer_phone" class="form-control"
                                value="{{ old('customer_phone') }}" placeholder="03XXXXXXXXX" required>

                        </div>


                        {{-- Email --}}
                        <div class="mb-3">

                            <label for="customer_email" class="form-label">
                                Email Address
                                <span class="text-muted">(Optional)</span>
                            </label>

                            <input type="email" id="customer_email" name="customer_email" class="form-control"
                                value="{{ old('customer_email') }}" placeholder="you@example.com">

                        </div>


                        {{-- City --}}
                        <div class="mb-3">

                            <label for="shipping_city" class="form-label">
                                City
                            </label>

                            <select id="shipping_city" name="shipping_city" class="form-select" required>

                                <option value="">
                                    Select City
                                </option>

                                <option value="Islamabad" @selected(old('shipping_city') === 'Islamabad')>
                                    Islamabad
                                </option>

                                <option value="Rawalpindi" @selected(old('shipping_city') === 'Rawalpindi')>
                                    Rawalpindi
                                </option>

                                <option value="Lahore" @selected(old('shipping_city') === 'Lahore')>
                                    Lahore
                                </option>

                                <option value="Karachi" @selected(old('shipping_city') === 'Karachi')>
                                    Karachi
                                </option>

                            </select>

                        </div>


                        {{-- Postcode --}}
                        <div class="mb-3">

                            <label for="shipping_postal_code" class="form-label">
                                Postal Code
                                <span class="text-muted">(Optional)</span>
                            </label>

                            <input type="text" id="shipping_postal_code" name="shipping_postal_code" class="form-control"
                                value="{{ old('shipping_postal_code') }}" placeholder="e.g. 44000">

                        </div>


                        {{-- Address --}}
                        <div class="mb-3">

                            <label for="shipping_address" class="form-label">
                                Complete Address
                            </label>

                            <textarea id="shipping_address" name="shipping_address" rows="4" class="form-control"
                                placeholder="House number, street, area, landmark..." required>{{ old('shipping_address') }}</textarea>

                        </div>


                        {{-- Notes --}}
                        <div class="mb-3">

                            <label for="customer_notes" class="form-label">

                                Order Notes

                                <span class="text-muted">
                                    (Optional)
                                </span>

                            </label>

                            <textarea id="customer_notes" name="customer_notes" rows="3" class="form-control"
                                placeholder="Any special instructions?">{{ old('customer_notes') }}</textarea>

                        </div>


                        {{-- Payment Method --}}
                        <h4 class="mt-5 mb-3">
                            Payment Method
                        </h4>


                        <div class="border rounded p-3">

                            <div class="form-check">

                                <input class="form-check-input" type="radio" name="payment_method" id="cod"
                                    value="cod" @checked(old('payment_method', 'cod') === 'cod')>

                                <label class="form-check-label" for="cod">

                                    <strong>
                                        Cash on Delivery
                                    </strong>

                                    <div class="small text-muted">
                                        Pay when your order arrives.
                                    </div>

                                </label>

                            </div>

                        </div>

                    </div>

                </div>


                {{-- Order Summary --}}
                <div class="col-lg-5">

                    <div class="border rounded p-4">

                        <h4 class="mb-4">
                            Order Summary
                        </h4>


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

                                        × {{ $item['quantity'] }}

                                    </div>

                                </div>

                                <span>
                                    Rs. {{ number_format($itemTotal, 2) }}
                                </span>

                            </div>
                        @endforeach


                        <hr>


                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Subtotal
                            </span>

                            <strong>
                                Rs. {{ number_format($subtotal, 2) }}
                            </strong>

                        </div>


                        <div class="d-flex justify-content-between mb-2">

                            <span>
                                Shipping
                            </span>

                            <span class="text-muted">
                                Calculated later
                            </span>

                        </div>


                        <hr>


                        <div class="d-flex justify-content-between">

                            <strong>
                                Total
                            </strong>

                            <strong class="fs-5">
                                Rs. {{ number_format($subtotal, 2) }}
                            </strong>

                        </div>


                        {{-- Submit Button --}}
                        <button type="submit" class="btn btn-storefront-primary w-100 mt-4">
                            Place Order
                        </button>


                        <a href="{{ route('storefront.cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                            Back to Cart
                        </a>

                    </div>

                </div>

            </div>

        </form>

    </div>

@endsection
