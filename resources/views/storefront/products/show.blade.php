@extends('layouts.storefront.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')

    <div class="container py-5">

        {{-- =========================================================
            Breadcrumb
        ========================================================== --}}

        <nav aria-label="breadcrumb" class="mb-4">

            <ol class="breadcrumb">

                <li class="breadcrumb-item">
                    <a href="{{ route('storefront.home') }}">
                        Home
                    </a>
                </li>

                <li class="breadcrumb-item">
                    <a href="{{ route('storefront.shop') }}">
                        Shop
                    </a>
                </li>

                <li class="breadcrumb-item active" aria-current="page">
                    {{ $product->name }}
                </li>

            </ol>

        </nav>


        {{-- =========================================================
            Product Details
        ========================================================== --}}

        <div class="row g-5">

            {{-- =====================================================
                Product Images
            ====================================================== --}}

            <div class="col-lg-6">

                @if ($product->images->isNotEmpty())

                    <div class="product-detail-main-image mb-3">

                        <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                            alt="{{ $product->images->first()->alt_text ?? $product->name }}" class="img-fluid rounded"
                            id="main-product-image">

                    </div>


                    <div class="row g-2">

                        @foreach ($product->images as $image)
                            <div class="col-3">

                                <button type="button" class="product-thumbnail-button border-0 bg-transparent p-0 w-100"
                                    onclick="document.getElementById('main-product-image').src='{{ asset('storage/' . $image->path) }}'">

                                    <img src="{{ asset('storage/' . $image->path) }}"
                                        alt="{{ $image->alt_text ?? $product->name }}"
                                        class="img-fluid rounded border product-thumbnail">

                                </button>

                            </div>
                        @endforeach

                    </div>
                @else
                    {{-- No Product Image --}}

                    <div class="product-detail-no-image rounded">

                        <div class="product-detail-no-image-icon">

                            <i class="bi bi-image"></i>

                        </div>

                        <p class="mb-0">
                            No product image available.
                        </p>

                    </div>

                @endif

            </div>


            {{-- =====================================================
                Product Information
            ====================================================== --}}

            <div class="col-lg-6">

                {{-- =================================================
                    Categories
                ================================================== --}}

                @if ($product->categories->isNotEmpty())

                    <div class="mb-3">

                        @foreach ($product->categories as $category)
                            <a href="{{ route('storefront.shop', ['category' => $category->slug]) }}"
                                class="badge bg-light text-dark text-decoration-none me-1">

                                {{ $category->name }}

                            </a>
                        @endforeach

                    </div>

                @endif


                {{-- =================================================
                    Product Name
                ================================================== --}}

                <h1 class="mb-3">
                    {{ $product->name }}
                </h1>


                {{-- =================================================
                    Short Description
                ================================================== --}}

                @if ($product->short_description)
                    <p class="text-muted mb-4">
                        {{ $product->short_description }}
                    </p>
                @endif


                {{-- =================================================
                    Price + Stock
                ================================================== --}}

                <div class="product-price-area mb-4">

                    <div class="d-flex align-items-center gap-3">

                        <span id="product-price" class="product-detail-price">

                            @if ($product->variants->isNotEmpty())
                                Rs.
                                {{ number_format($product->variants->first()->price, 2) }}
                            @else
                                Price unavailable
                            @endif

                        </span>

                    </div>


                    <div id="product-stock-status" class="mt-2">

                        @if ($product->variants->isNotEmpty())

                            @if ($product->variants->first()->stock_quantity > 0)
                                <span class="text-success">

                                    <i class="bi bi-check-circle-fill me-1"></i>

                                    In Stock

                                </span>
                            @else
                                <span class="text-danger">

                                    <i class="bi bi-x-circle-fill me-1"></i>

                                    Out of Stock

                                </span>
                            @endif

                        @endif

                    </div>

                </div>


                {{-- =================================================
                    Product Description
                ================================================== --}}

                @if ($product->description)
                    <div class="product-detail-description mb-4">

                        {!! nl2br(e($product->description)) !!}

                    </div>
                @endif


                {{-- =================================================
                    Product Variants
                ================================================== --}}

                @if ($product->variants->isNotEmpty())

                    <div class="product-variants mb-4">

                        <h5 class="mb-3">
                            Select Weight
                        </h5>


                        <div class="row g-2">

                            @foreach ($product->variants as $index => $variant)
                                <div class="col-6 col-md-4">

                                    <input type="radio" class="btn-check product-variant-input" name="variant_id"
                                        id="variant-{{ $variant->id }}" value="{{ $variant->id }}"
                                        data-price="{{ $variant->price }}" data-stock="{{ $variant->stock_quantity }}"
                                        @checked($index === 0 && $variant->stock_quantity > 0) @disabled($variant->stock_quantity <= 0)>

                                    <label class="product-variant-option w-100" for="variant-{{ $variant->id }}">

                                        <span class="d-block fw-semibold">

                                            {{ $variant->name }}

                                        </span>


                                        <span class="d-block mt-1">

                                            Rs.
                                            {{ number_format($variant->price, 2) }}

                                        </span>


                                        @if ($variant->stock_quantity > 0)
                                            <small class="text-success">

                                                In Stock

                                            </small>
                                        @else
                                            <small class="text-danger">

                                                Out of Stock

                                            </small>
                                        @endif

                                    </label>

                                </div>
                            @endforeach

                        </div>

                    </div>


                    {{-- =================================================
                        Quantity
                    ================================================== --}}

                    <div class="product-quantity-area mb-4">

                        <label for="quantity" class="form-label fw-semibold">

                            Quantity

                        </label>


                        <div class="product-quantity-control">

                            <button type="button" class="quantity-btn" id="quantity-minus" aria-label="Decrease quantity">

                                <i class="bi bi-dash"></i>

                            </button>


                            <input type="number" id="quantity" name="quantity" value="1" min="1"
                                max="99" readonly>


                            <button type="button" class="quantity-btn" id="quantity-plus" aria-label="Increase quantity">

                                <i class="bi bi-plus"></i>

                            </button>

                        </div>

                    </div>

                @endif


                {{-- =================================================
                    Product Actions
                ================================================== --}}

                <div class="product-actions">

                    <div class="d-flex flex-column flex-sm-row gap-2">


                        {{-- =================================================
                            Product Actions
                        ================================================== --}}

                        <div class="product-actions">

                            <form method="POST" action="{{ route('storefront.cart.store') }}" id="add-to-cart-form"
                                class="product-action-form">

                                @csrf

                                <input type="hidden" name="variant_id" id="selected-variant-id" value="">

                                <input type="hidden" name="quantity" id="cart-quantity" value="1">

                                <div class="product-action-buttons">

                                    {{-- Add To Cart --}}
                                    <button type="submit" id="add-to-cart-btn"
                                        class="product-action-btn product-add-to-cart" @disabled($product->variants->isEmpty() || $product->variants->first()->stock_quantity <= 0)>

                                        <i class="bi bi-cart-plus"></i>

                                        <span>
                                            Add to Cart
                                        </span>

                                    </button>


                                    {{-- Buy Now --}}
                                    <button type="button" id="buy-now-btn" class="product-action-btn product-buy-now"
                                        @disabled($product->variants->isEmpty() || $product->variants->first()->stock_quantity <= 0)>

                                        <i class="bi bi-lightning-charge"></i>

                                        <span>
                                            Buy Now
                                        </span>

                                    </button>

                                </div>

                            </form>

                        </div>


                        {{-- =================================================
                            Buy Now
                        ================================================== --}}

                        {{-- <form method="POST" action="{{ route('storefront.cart.store') }}" id="buy-now-form"
                            class="flex-grow-1">

                            @csrf


                            <input type="hidden" name="variant_id" id="buy-now-variant-id" value="">


                            <input type="hidden" name="quantity" id="buy-now-quantity" value="1">


                            <input type="hidden" name="buy_now" value="1">


                            <button type="submit" id="buy-now-btn" class="btn btn-outline-dark w-100"
                                @disabled($product->variants->isEmpty() || $product->variants->first()->stock_quantity <= 0)>

                                <i class="bi bi-lightning-charge me-2"></i>

                                Buy Now

                            </button>

                        </form> --}}

                    </div>

                </div>


                {{-- =================================================
                    Back To Shop
                ================================================== --}}

                <div class="mt-3">

                    <a href="{{ route('storefront.shop') }}" class="btn btn-outline-secondary">

                        Back to Shop

                    </a>

                </div>

            </div>

        </div>


        {{-- =========================================================
            Related Products

            IMPORTANT:
            This section is OUTSIDE the product-detail row.
        ========================================================== --}}

        @if ($relatedProducts->isNotEmpty())

            <section class="storefront-product-related mt-5">

                <div class="pt-5">

                    {{-- =================================================
                        Related Products Heading
                    ================================================== --}}

                    <div class="storefront-related-heading text-center mb-5">

                        <span class="storefront-section-eyebrow">

                            You May Also Like

                        </span>


                        <h2 class="storefront-related-title">

                            Related Products

                        </h2>


                        <p class="storefront-related-description">

                            Explore more premium dry fruits from our collection.

                        </p>

                    </div>


                    {{-- =================================================
                        Related Products Grid
                    ================================================== --}}

                    <div class="row g-4">

                        @foreach ($relatedProducts as $relatedProduct)
                            <div class="col-6 col-md-4 col-lg-3">

                                <x-storefront.product-card :product="$relatedProduct" />

                            </div>
                        @endforeach

                    </div>

                </div>

            </section>

        @endif

    </div>

@endsection


{{-- =========================================================
    Product Page JavaScript
========================================================== --}}

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | Variant Elements
            |--------------------------------------------------------------------------
            */

            const variantInputs = document.querySelectorAll(
                '.product-variant-input'
            );


            /*
            |--------------------------------------------------------------------------
            | Product Information
            |--------------------------------------------------------------------------
            */

            const priceElement = document.getElementById(
                'product-price'
            );

            const stockElement = document.getElementById(
                'product-stock-status'
            );


            /*
            |--------------------------------------------------------------------------
            | Quantity Controls
            |--------------------------------------------------------------------------
            */

            const quantityInput = document.getElementById(
                'quantity'
            );

            const minusButton = document.getElementById(
                'quantity-minus'
            );

            const plusButton = document.getElementById(
                'quantity-plus'
            );


            /*
            |--------------------------------------------------------------------------
            | Add To Cart
            |--------------------------------------------------------------------------
            */

            const addToCartButton = document.getElementById(
                'add-to-cart-btn'
            );

            const selectedVariantId = document.getElementById(
                'selected-variant-id'
            );

            const cartQuantity = document.getElementById(
                'cart-quantity'
            );


            /*
            |--------------------------------------------------------------------------
            | Buy Now
            |--------------------------------------------------------------------------
            */

            const buyNowButton = document.getElementById(
                'buy-now-btn'
            );

            const buyNowVariantId = document.getElementById(
                'buy-now-variant-id'
            );

            const buyNowQuantity = document.getElementById(
                'buy-now-quantity'
            );


            /*
            |--------------------------------------------------------------------------
            | Update Selected Variant
            |--------------------------------------------------------------------------
            */

            function updateVariant() {

                const selectedVariant = document.querySelector(
                    '.product-variant-input:checked'
                );


                /*
                |--------------------------------------------------------------------------
                | No Variant Selected
                |--------------------------------------------------------------------------
                */

                if (!selectedVariant) {

                    if (selectedVariantId) {

                        selectedVariantId.value = '';

                    }


                    if (buyNowVariantId) {

                        buyNowVariantId.value = '';

                    }


                    if (addToCartButton) {

                        addToCartButton.disabled = true;

                    }


                    if (buyNowButton) {

                        buyNowButton.disabled = true;

                    }

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Variant ID
                |--------------------------------------------------------------------------
                */

                if (selectedVariantId) {

                    selectedVariantId.value =
                        selectedVariant.value;

                }


                if (buyNowVariantId) {

                    buyNowVariantId.value =
                        selectedVariant.value;

                }


                /*
                |--------------------------------------------------------------------------
                | Price
                |--------------------------------------------------------------------------
                */

                const price = parseFloat(
                    selectedVariant.dataset.price
                );


                /*
                |--------------------------------------------------------------------------
                | Stock
                |--------------------------------------------------------------------------
                */

                const stock = parseInt(
                    selectedVariant.dataset.stock,
                    10
                );


                /*
                |--------------------------------------------------------------------------
                | Update Price
                |--------------------------------------------------------------------------
                */

                if (
                    priceElement &&
                    !Number.isNaN(price)
                ) {

                    priceElement.textContent =
                        'Rs. ' +
                        price.toLocaleString(
                            'en-PK', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            }
                        );

                }


                /*
                |--------------------------------------------------------------------------
                | Update Stock Status
                |--------------------------------------------------------------------------
                */

                if (
                    stockElement &&
                    stock > 0
                ) {

                    stockElement.innerHTML = `
                        <span class="text-success">

                            <i class="bi bi-check-circle-fill me-1"></i>

                            In Stock

                        </span>
                    `;

                } else if (stockElement) {

                    stockElement.innerHTML = `
                        <span class="text-danger">

                            <i class="bi bi-x-circle-fill me-1"></i>

                            Out of Stock

                        </span>
                    `;

                }


                /*
                |--------------------------------------------------------------------------
                | Add To Cart Button State
                |--------------------------------------------------------------------------
                */

                if (addToCartButton) {

                    addToCartButton.disabled =
                        stock <= 0;

                }


                /*
                |--------------------------------------------------------------------------
                | Buy Now Button State
                |--------------------------------------------------------------------------
                */

                if (buyNowButton) {

                    buyNowButton.disabled =
                        stock <= 0;

                }


                /*
                |--------------------------------------------------------------------------
                | Quantity Maximum
                |--------------------------------------------------------------------------
                */

                if (quantityInput) {

                    const maximum =
                        Math.min(
                            stock > 0 ? stock : 1,
                            99
                        );

                    quantityInput.max =
                        maximum;

                    quantityInput.value =
                        1;

                }


                /*
                |--------------------------------------------------------------------------
                | Reset Add To Cart Quantity
                |--------------------------------------------------------------------------
                */

                if (cartQuantity) {

                    cartQuantity.value =
                        1;

                }


                /*
                |--------------------------------------------------------------------------
                | Reset Buy Now Quantity
                |--------------------------------------------------------------------------
                */

                if (buyNowQuantity) {

                    buyNowQuantity.value =
                        1;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Variant Selection
            |--------------------------------------------------------------------------
            */

            variantInputs.forEach(function(input) {

                input.addEventListener(
                    'change',
                    updateVariant
                );

            });


            /*
            |--------------------------------------------------------------------------
            | Decrease Quantity
            |--------------------------------------------------------------------------
            */

            minusButton?.addEventListener(
                'click',
                function() {

                    if (!quantityInput) {

                        return;

                    }


                    let quantity =
                        parseInt(
                            quantityInput.value,
                            10
                        ) || 1;


                    if (quantity > 1) {

                        quantity--;

                        quantityInput.value =
                            quantity;


                        /*
                        | Add To Cart Quantity
                        */

                        if (cartQuantity) {

                            cartQuantity.value =
                                quantity;

                        }


                        /*
                        | Buy Now Quantity
                        */

                        if (buyNowQuantity) {

                            buyNowQuantity.value =
                                quantity;

                        }

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Increase Quantity
            |--------------------------------------------------------------------------
            */

            plusButton?.addEventListener(
                'click',
                function() {

                    if (!quantityInput) {

                        return;

                    }


                    let quantity =
                        parseInt(
                            quantityInput.value,
                            10
                        ) || 1;


                    let maximum =
                        parseInt(
                            quantityInput.max,
                            10
                        ) || 1;


                    if (quantity < maximum) {

                        quantity++;

                        quantityInput.value =
                            quantity;


                        /*
                        | Add To Cart Quantity
                        */

                        if (cartQuantity) {

                            cartQuantity.value =
                                quantity;

                        }


                        /*
                        | Buy Now Quantity
                        */

                        if (buyNowQuantity) {

                            buyNowQuantity.value =
                                quantity;

                        }

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Initial State
            |--------------------------------------------------------------------------
            */

            updateVariant();

        });
    </script>
@endpush
