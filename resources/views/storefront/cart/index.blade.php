@extends('layouts.storefront.app')

@section('title', 'Shopping Cart - ' . config('app.name'))

@section('content')

    <div class="storefront-cart-page">

        {{-- Cart Header --}}
        <section class="storefront-cart-header">

            <div class="container">

                <div class="storefront-cart-header-inner">

                    <span class="storefront-cart-eyebrow">
                        Your Selection
                    </span>

                    <h1>
                        Shopping Cart
                    </h1>

                    <p>
                        Review your selected dry fruits before continuing.
                    </p>

                </div>

            </div>

        </section>


        <div class="container py-5">

            {{-- Errors --}}
            @if ($errors->any())

                <div class="alert alert-danger storefront-cart-alert">

                    @foreach ($errors->all() as $error)
                        <div>
                            {{ $error }}
                        </div>
                    @endforeach

                </div>

            @endif


            {{-- Success --}}
            @if (session('success'))
                <div class="alert alert-success storefront-cart-alert">

                    {{ session('success') }}

                </div>
            @endif


            {{-- Empty Cart --}}
            @if (empty($cart))

                <div class="storefront-empty-cart">

                    <div class="storefront-empty-cart-icon">

                        <i class="bi bi-basket3"></i>

                    </div>

                    <h2>
                        Your cart is empty
                    </h2>

                    <p>
                        Looks like you haven't added any dry fruits yet.
                    </p>

                    <a href="{{ route('storefront.shop') }}" class="btn btn-storefront-primary">
                        Continue Shopping
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>

                </div>

        </div>
    @else
        @php
            $subtotal = 0;
        @endphp


        <div class="row g-4 align-items-start">

            {{-- Cart Items --}}
            <div class="col-lg-8">

                <div class="storefront-cart-items">

                    @foreach ($cart as $item)
                        @php
                            $itemTotal = $item['price'] * $item['quantity'];
                            $subtotal += $itemTotal;
                        @endphp


                        <article class="storefront-cart-item">

                            {{-- Product Image --}}
                            <div class="storefront-cart-item-image">

                                @if (!empty($item['image']))
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                        loading="lazy">
                                @else
                                    <div class="storefront-cart-no-image">
                                        <i class="bi bi-image"></i>
                                    </div>
                                @endif

                            </div>


                            {{-- Product Information --}}
                            <div class="storefront-cart-item-info">

                                <h3>
                                    {{ $item['name'] }}
                                </h3>

                                <div class="storefront-cart-variant">
                                    {{ $item['variant_name'] }}
                                </div>

                                <div class="storefront-cart-price">
                                    Rs. {{ number_format($item['price'], 2) }}
                                </div>

                            </div>


                            {{-- Quantity --}}
                            <div class="storefront-cart-quantity">

                                <form method="POST" action="{{ route('storefront.cart.update', $item['variant_id']) }}">

                                    @csrf

                                    @method('PATCH')

                                    <div class="storefront-quantity-control">

                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1"
                                            max="{{ $item['stock'] }}" aria-label="Quantity">

                                        <button type="submit" title="Update quantity">
                                            <i class="bi bi-check2"></i>
                                        </button>

                                    </div>

                                </form>

                            </div>


                            {{-- Total --}}
                            <div class="storefront-cart-item-total">

                                <strong>
                                    Rs. {{ number_format($itemTotal, 2) }}
                                </strong>

                            </div>


                            {{-- Remove --}}
                            <div class="storefront-cart-remove">

                                <form method="POST" action="{{ route('storefront.cart.destroy', $item['variant_id']) }}">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit" title="Remove item" aria-label="Remove {{ $item['name'] }}">
                                        <i class="bi bi-trash3"></i>
                                    </button>

                                </form>

                            </div>

                        </article>
                    @endforeach

                </div>


                {{-- Continue Shopping --}}
                <div class="storefront-cart-actions">

                    <a href="{{ route('storefront.shop') }}" class="storefront-cart-continue">
                        <i class="bi bi-arrow-left me-2"></i>
                        Continue Shopping
                    </a>


                    {{-- Clear Cart --}}
                    <form method="POST" action="{{ route('storefront.cart.clear') }}">

                        @csrf

                        @method('DELETE')

                        <button type="submit" class="storefront-cart-clear">
                            <i class="bi bi-trash3 me-1"></i>
                            Clear Cart
                        </button>

                    </form>

                </div>

            </div>


            {{-- Order Summary --}}
            <div class="col-lg-4">

                <aside class="storefront-cart-summary">

                    <div class="storefront-cart-summary-header">

                        <h2>
                            Order Summary
                        </h2>

                    </div>


                    <div class="storefront-cart-summary-row">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            Rs. {{ number_format($subtotal, 2) }}
                        </strong>

                    </div>


                    <div class="storefront-cart-summary-row">

                        <span>
                            Delivery
                        </span>

                        <span class="storefront-cart-summary-muted">
                            Calculated at checkout
                        </span>

                    </div>


                    <div class="storefront-cart-summary-divider"></div>


                    <div class="storefront-cart-summary-total">

                        <span>
                            Total
                        </span>

                        <strong>
                            Rs. {{ number_format($subtotal, 2) }}
                        </strong>

                    </div>


                    {{-- Checkout will be connected later --}}
                    <a href="{{ route('storefront.checkout.index') }}" class="btn btn-storefront-primary">
                        Proceed to Checkout
                        <i class="bi bi-arrow-right ms-2"></i>
                    </a>



                    <div class="storefront-cart-trust">

                        <div>
                            <i class="bi bi-shield-check"></i>
                            Secure Checkout
                        </div>

                        <div>
                            <i class="bi bi-truck"></i>
                            Nationwide Delivery
                        </div>

                    </div>

                </aside>

            </div>

        </div>

        @endif

    </div>

    </div>

@endsection
