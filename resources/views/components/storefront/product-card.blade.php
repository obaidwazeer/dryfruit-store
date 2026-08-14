@php
    $hasStock = $product->variants->contains(fn($variant) => $variant->is_active && $variant->stock_quantity > 0);
@endphp

@php
    $primaryImage = $product->images->first();

    $activeVariants = $product->variants->where('is_active', true);

    $startingVariant = $activeVariants->first();

    $startingPrice = $startingVariant?->price;

    $compareAtPrice = $startingVariant?->compare_at_price;

    $hasDiscount =
        $compareAtPrice !== null && $startingPrice !== null && (float) $compareAtPrice > (float) $startingPrice;
@endphp

<div class="storefront-product-card">

    {{-- Product Image --}}
    <div class="storefront-product-card-image">

        @if ($product->is_featured)
            <span class="storefront-product-badge">
                Featured
            </span>
        @endif

        <a href="{{ route('storefront.products.show', $product->slug) }}" class="storefront-product-image-link">

            @if ($primaryImage)
                <img src="{{ asset('storage/' . $primaryImage->path) }}"
                    alt="{{ $primaryImage->alt_text ?? $product->name }}" class="storefront-product-image" loading="lazy">
            @else
                <img src="{{ asset('assets/storefront/images/products/default-product.jpg') }}"
                    alt="{{ $product->name }}" class="storefront-product-image" loading="lazy">
            @endif

        </a>

    </div>


    {{-- Product Information --}}
    <div class="storefront-product-card-body">

        {{-- Categories --}}
        @if ($product->categories->isNotEmpty())

            <div class="storefront-product-categories">

                @foreach ($product->categories->take(2) as $category)
                    <span class="storefront-product-category">
                        {{ $category->name }}
                    </span>
                @endforeach

            </div>

        @endif


        {{-- Product Name --}}
        <h3 class="storefront-product-name">

            <a href="#">

                {{ $product->name }}

            </a>

        </h3>


        {{-- Short Description --}}
        @if ($product->short_description)
            <p class="storefront-product-description">

                {{ $product->short_description }}

            </p>
        @endif


        {{-- Price --}}
        <div class="storefront-product-price">

            @if ($startingPrice !== null)

                <span class="storefront-product-price-label">
                    From
                </span>

                <span class="storefront-product-price-current">

                    Rs. {{ number_format((float) $startingPrice, 2) }}

                </span>

                @if ($hasDiscount)
                    <span class="storefront-product-price-old">

                        Rs. {{ number_format((float) $compareAtPrice, 2) }}

                    </span>
                @endif
            @else
                <span class="storefront-product-price-unavailable">
                    Price unavailable
                </span>

            @endif

            @if ($hasStock)
                <span class="badge bg-success">
                    In Stock
                </span>
            @else
                <span class="badge bg-secondary">
                    Out of Stock
                </span>
            @endif

        </div>


        {{-- Variant Information --}}
        @if ($activeVariants->count() > 1)
            <div class="storefront-product-variants">

                {{ $activeVariants->count() }} sizes available

            </div>
        @elseif ($activeVariants->count() === 1)
            <div class="storefront-product-variants">

                {{ $startingVariant->weight_grams >= 1000
                    ? number_format($startingVariant->weight_grams / 1000, 2) . ' kg'
                    : $startingVariant->weight_grams . ' g' }}

            </div>
        @endif


        {{-- Action --}}
        <div class="storefront-product-card-action">

            {{-- <a href="#" class="btn btn-storefront-primary w-100"> --}}
            <a href="{{ route('storefront.products.show', $product->slug) }}" class="btn btn-storefront-primary w-100">

                View Product

                <i class="bi bi-arrow-right ms-2"></i>

            </a>

        </div>

    </div>

</div>
