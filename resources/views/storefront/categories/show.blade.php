@extends('layouts.storefront.app')

@section('title', $category->name . ' - ' . config('app.name'))

@section('content')

    <div class="storefront-category-page">

        {{-- =====================================================
        Breadcrumb
    ====================================================== --}}

        <section class="storefront-breadcrumb-section">

            <div class="container">

                <nav aria-label="breadcrumb">

                    <ol class="breadcrumb mb-0">

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
                            {{ $category->name }}
                        </li>

                    </ol>

                </nav>

            </div>

        </section>


        {{-- =====================================================
        Category Header
    ====================================================== --}}

        <section class="storefront-category-header">

            <div class="container">

                <div class="text-center">

                    <span class="storefront-section-eyebrow">
                        Our Collection
                    </span>

                    <h1 class="storefront-category-title">
                        {{ $category->name }}
                    </h1>

                    <p class="storefront-category-description">
                        Explore our premium {{ strtolower($category->name) }}
                        collection, carefully selected for quality,
                        freshness and exceptional taste.
                    </p>

                </div>

            </div>

        </section>


        {{-- =====================================================
        Products
    ====================================================== --}}

        <section class="storefront-category-products">

            <div class="container">

                <div class="d-flex justify-content-between align-items-center mb-4">

                    <div>

                        <h2 class="h4 mb-1">
                            {{ $category->name }} Products
                        </h2>

                        <p class="text-muted mb-0">
                            {{ $products->total() }}
                            {{ $products->total() === 1 ? 'product' : 'products' }}
                            available
                        </p>

                    </div>


                    <a href="{{ route('storefront.shop') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-grid me-1"></i>
                        View All Products
                    </a>

                </div>


                @if ($products->isNotEmpty())

                    <div class="row g-4">

                        @foreach ($products as $product)
                            <div class="col-6 col-md-4 col-lg-3">

                                <x-storefront.product-card :product="$product" />

                            </div>
                        @endforeach

                    </div>


                    {{-- Pagination --}}

                    <div class="mt-5">

                        {{ $products->links() }}

                    </div>
                @else
                    <div class="text-center py-5">

                        <div class="mb-3">

                            <i class="bi bi-box-seam fs-1 text-muted"></i>

                        </div>

                        <h4>
                            No products available
                        </h4>

                        <p class="text-muted">
                            There are currently no products available
                            in this category.
                        </p>

                        <a href="{{ route('storefront.shop') }}" class="btn btn-storefront-primary">
                            Continue Shopping
                        </a>

                    </div>

                @endif

            </div>

        </section>

    </div>

@endsection
