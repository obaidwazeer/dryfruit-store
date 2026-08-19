@extends('layouts.storefront.app')

@section('title', 'Payment Assistance - ' . config('app.name'))

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-lg-8">

                {{-- Header --}}
                <div class="text-center mb-5">

                    <span class="storefront-section-eyebrow">
                        Payment Assistance
                    </span>

                    <h1 class="fw-bold mt-2">
                        We Can Help You Complete Your Order
                    </h1>

                    <p class="text-muted mt-3 mb-0">
                        Some products in your cart require additional
                        payment arrangements. Please provide your details
                        and our team will contact you.
                    </p>

                </div>


                {{-- Assistance Card --}}
                <div class="border rounded-4 p-4 p-md-5 shadow-sm">

                    {{-- Notice --}}
                    <div class="alert alert-warning mb-4">

                        <div class="d-flex gap-3">

                            <i class="bi bi-info-circle-fill fs-4"></i>

                            <div>

                                <strong>
                                    Payment assistance is required
                                </strong>

                                <p class="mb-0 mt-1">
                                    We currently cannot process this
                                    combination of products through our
                                    available payment methods.
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Cart Summary --}}
                    <h5 class="mb-3">
                        Your Cart
                    </h5>


                    <div class="border rounded p-3 mb-4">

                        @foreach ($cart as $item)
                            @php
                                $itemTotal = $item['price'] * $item['quantity'];
                            @endphp

                            <div
                                class="d-flex justify-content-between
                                    align-items-start
                                    py-2">

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

                                <span class="fw-semibold">

                                    Rs.
                                    {{ number_format($itemTotal, 2) }}

                                </span>

                            </div>
                        @endforeach


                        <hr>


                        <div class="d-flex justify-content-between">

                            <strong>
                                Estimated Subtotal
                            </strong>

                            <strong>
                                Rs.
                                {{ number_format($subtotal, 2) }}
                            </strong>

                        </div>

                    </div>


                    {{-- Customer Form --}}
                    <form method="POST" action="{{ route('storefront.payment-assistance.store') }}">

                        @csrf


                        {{-- Name --}}
                        <div class="mb-3">

                            <label for="customer_name" class="form-label">
                                Full Name
                            </label>

                            <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name') }}"
                                class="form-control @error('customer_name') is-invalid @enderror"
                                placeholder="Enter your full name" required>

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

                            <input type="text" id="customer_phone" name="customer_phone"
                                value="{{ old('customer_phone') }}"
                                class="form-control @error('customer_phone') is-invalid @enderror" placeholder="03XXXXXXXXX"
                                required>

                            @error('customer_phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Email --}}
                        <div class="mb-4">

                            <label for="customer_email" class="form-label">
                                Email Address
                                <span class="text-muted">
                                    (Optional)
                                </span>
                            </label>

                            <input type="email" id="customer_email" name="customer_email"
                                value="{{ old('customer_email') }}"
                                class="form-control @error('customer_email') is-invalid @enderror"
                                placeholder="you@example.com">

                            @error('customer_email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                        </div>


                        {{-- Actions --}}
                        <div
                            class="d-flex flex-column
                                flex-sm-row
                                gap-3">

                            <a href="{{ route('storefront.cart.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left me-2"></i>

                                Back to Cart
                            </a>


                            <button type="submit" class="btn btn-storefront-primary flex-grow-1">

                                <i class="bi bi-headset me-2"></i>

                                Request Payment Assistance

                            </button>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

@endsection
