@extends('layouts.storefront.app')

@section('title', $product->name . ' - ' . config('app.name'))

@section('content')

    <div class="container py-5">

        {{-- Breadcrumb --}}
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

                <li class="breadcrumb-item active">
                    {{ $product->name }}
                </li>

            </ol>

        </nav>


        <div class="row g-5">

            {{-- Product Images --}}
            <div class="col-lg-6">

                @if ($product->images->isNotEmpty())

                    <div class="mb-3">

                        <img src="{{ asset('storage/' . $product->images->first()->path) }}"
                            alt="{{ $product->images->first()->alt_text ?? $product->name }}" class="img-fluid rounded"
                            id="main-product-image">

                    </div>


                    <div class="row g-2">

                        @foreach ($product->images as $image)
                            <div class="col-3">

                                <img src="{{ asset('storage/' . $image->path) }}"
                                    alt="{{ $image->alt_text ?? $product->name }}"
                                    class="img-fluid rounded border product-thumbnail" style="cursor:pointer;"
                                    onclick="document.getElementById('main-product-image').src='{{ asset('storage/' . $image->path) }}'">

                            </div>
                        @endforeach

                    </div>
                @else
                    <div class="bg-light rounded p-5 text-center">

                        <span>
                            No product image available.
                        </span>

                    </div>

                @endif

            </div>


            {{-- Product Information --}}
            <div class="col-lg-6">

                <div class="mb-2">

                    @foreach ($product->categories as $category)
                        <span class="badge bg-light text-dark me-1">
                            {{ $category->name }}
                        </span>
                    @endforeach

                </div>


                <h1 class="mb-3">
                    {{ $product->name }}
                </h1>


                @if ($product->short_description)
                    <p class="text-muted mb-4">
                        {{ $product->short_description }}
                    </p>
                @endif

                <div class="product-price-area mb-4">

                    <div class="d-flex align-items-center gap-3">

                        <span id="product-price" class="product-detail-price">
                            @if ($product->variants->isNotEmpty())
                                Rs. {{ number_format($product->variants->first()->price, 2) }}
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


                @if ($product->description)
                    <div class="mb-4">

                        {!! nl2br(e($product->description)) !!}

                    </div>
                @endif


                {{-- Variants --}}
                <div class="mb-4">

                    {{-- <h5 class="mb-3">
                        Select Weight
                    </h5> --}}


                    {{-- @forelse ($product->variants as $variant)
                        <div class="border rounded p-3 mb-2">

                            <strong>
                                {{ $variant->name }}
                            </strong>

                            <div class="mt-1">

                                Rs. {{ number_format($variant->price, 2) }}

                            </div>


                            @if ($variant->stock_quantity > 0)
                                <small class="text-success">
                                    In Stock
                                </small>
                            @else
                                <small class="text-danger">
                                    Out of Stock
                                </small>
                            @endif

                        </div>

                    @empty

                        <p class="text-danger">
                            No variants are currently available.
                        </p>
                    @endforelse --}}
                    {{-- Product Variants --}}
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
                                                Rs. {{ number_format($variant->price, 2) }}
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

                    @endif

                    @if ($product->variants->isNotEmpty())
                        <div class="product-quantity-area mb-4">

                            <label for="quantity" class="form-label fw-semibold">
                                Quantity
                            </label>

                            <div class="product-quantity-control">

                                <button type="button" class="quantity-btn" id="quantity-minus">
                                    <i class="bi bi-dash"></i>
                                </button>

                                <input type="number" id="quantity" name="quantity" value="1" min="1"
                                    max="99" readonly>

                                <button type="button" class="quantity-btn" id="quantity-plus">
                                    <i class="bi bi-plus"></i>
                                </button>

                            </div>

                        </div>
                    @endif

                    <div class="product-actions">

                        {{-- <button type="button" id="add-to-cart-btn" class="btn btn-storefront-primary product-add-to-cart"
                            @disabled($product->variants->isEmpty() || $product->variants->first()->stock_quantity <= 0)>
                            <i class="bi bi-cart-plus me-2"></i>

                            Add to Cart
                        </button> --}}
                        <form method="POST" action="{{ route('storefront.cart.store') }}" id="add-to-cart-form">
                            @csrf

                            <input type="hidden" name="variant_id" id="selected-variant-id" value="">

                            <input type="hidden" name="quantity" id="cart-quantity" value="1">

                            <button type="submit" id="add-to-cart-btn"
                                class="btn btn-storefront-primary product-add-to-cart" @disabled($product->variants->isEmpty() || $product->variants->first()->stock_quantity <= 0)>
                                <i class="bi bi-cart-plus me-2"></i>

                                Add to Cart
                            </button>

                        </form>

                    </div>

                </div>


                <a href="{{ route('storefront.shop') }}" class="btn btn-outline-secondary">
                    Back to Shop
                </a>

            </div>

        </div>

    </div>

@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const variantInputs = document.querySelectorAll(
                '.product-variant-input'
            );

            const priceElement = document.getElementById(
                'product-price'
            );

            const stockElement = document.getElementById(
                'product-stock-status'
            );

            const quantityInput = document.getElementById(
                'quantity'
            );

            const minusButton = document.getElementById(
                'quantity-minus'
            );

            const plusButton = document.getElementById(
                'quantity-plus'
            );

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

                    if (addToCartButton) {
                        addToCartButton.disabled = true;
                    }

                    return;
                }


                /*
                |--------------------------------------------------------------------------
                | Variant ID
                |--------------------------------------------------------------------------
                */

                selectedVariantId.value =
                    selectedVariant.value;


                /*
                |--------------------------------------------------------------------------
                | Variant Price
                |--------------------------------------------------------------------------
                */

                const price = parseFloat(
                    selectedVariant.dataset.price
                );


                /*
                |--------------------------------------------------------------------------
                | Variant Stock
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

                if (priceElement && !isNaN(price)) {

                    priceElement.textContent =
                        'Rs. ' +
                        price.toLocaleString('en-PK', {
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        });

                }


                /*
                |--------------------------------------------------------------------------
                | Update Stock Status
                |--------------------------------------------------------------------------
                */

                if (stock > 0) {

                    stockElement.innerHTML = `
                <span class="text-success">
                    <i class="bi bi-check-circle-fill me-1"></i>
                    In Stock
                </span>
            `;


                    /*
                    |--------------------------------------------------------------------------
                    | Enable Add To Cart
                    |--------------------------------------------------------------------------
                    */

                    addToCartButton.disabled = false;


                    /*
                    |--------------------------------------------------------------------------
                    | Set Maximum Quantity
                    |--------------------------------------------------------------------------
                    */

                    quantityInput.max = Math.min(
                        stock,
                        99
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | Reset Quantity
                    |--------------------------------------------------------------------------
                    */

                    quantityInput.value = 1;

                    cartQuantity.value = 1;

                } else {

                    stockElement.innerHTML = `
                <span class="text-danger">
                    <i class="bi bi-x-circle-fill me-1"></i>
                    Out of Stock
                </span>
            `;


                    /*
                    |--------------------------------------------------------------------------
                    | Disable Add To Cart
                    |--------------------------------------------------------------------------
                    */

                    addToCartButton.disabled = true;


                    quantityInput.value = 1;

                    cartQuantity.value = 1;

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

            minusButton?.addEventListener('click', function() {

                let quantity = parseInt(
                    quantityInput.value,
                    10
                );


                if (quantity > 1) {

                    quantity--;

                    quantityInput.value = quantity;

                    cartQuantity.value = quantity;

                }

            });


            /*
            |--------------------------------------------------------------------------
            | Increase Quantity
            |--------------------------------------------------------------------------
            */

            plusButton?.addEventListener('click', function() {

                let quantity = parseInt(
                    quantityInput.value,
                    10
                );

                let maximum = parseInt(
                    quantityInput.max,
                    10
                );


                if (quantity < maximum) {

                    quantity++;

                    quantityInput.value = quantity;

                    cartQuantity.value = quantity;

                }

            });


            /*
            |--------------------------------------------------------------------------
            | Initial State
            |--------------------------------------------------------------------------
            */

            updateVariant();

        });
    </script>
@endpush
