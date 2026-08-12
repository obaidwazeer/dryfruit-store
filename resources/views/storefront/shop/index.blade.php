@extends('layouts.storefront.app')

@section('title', 'Shop - ' . config('app.name'))

@section('meta_description', 'Shop premium quality almonds, cashews, pistachios, walnuts, dates and other dry fruits.')

@section('content')

    <section class="storefront-shop-section py-5">

        <div class="container">

            {{-- Page Header --}}
            <div class="row align-items-center mb-4">

                <div class="col-lg-7">

                    <span class="storefront-section-eyebrow">
                        Our Collection
                    </span>

                    <h1 class="storefront-section-title mb-2">
                        Premium Dry Fruits
                    </h1>

                    <p class="text-muted mb-0">
                        Carefully selected dry fruits delivered fresh to your doorstep.
                    </p>

                </div>

                <div class="col-lg-5 mt-3 mt-lg-0">

                    <form method="GET" action="{{ route('storefront.shop') }}" class="storefront-shop-search">

                        <div class="input-group">

                            <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search dry fruits..." aria-label="Search products">

                            <button type="submit" class="btn btn-storefront-primary">
                                <i class="bi bi-search"></i>
                                Search
                            </button>

                        </div>

                    </form>

                </div>

            </div>


            {{-- Filters --}}
            <div class="card storefront-filter-card border-0 mb-4">

                <div class="card-body">

                    <form method="GET" action="{{ route('storefront.shop') }}">

                        @if (request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif

                        <div class="row g-3 align-items-end">

                            {{-- Category --}}
                            <div class="col-md-5">

                                <label for="category" class="form-label fw-semibold">
                                    Category
                                </label>

                                <select id="category" name="category" class="form-select">

                                    <option value="">
                                        All Categories
                                    </option>

                                    @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach

                                </select>

                            </div>


                            {{-- Sorting --}}
                            <div class="col-md-5">

                                <label for="sort" class="form-label fw-semibold">
                                    Sort By
                                </label>

                                <select id="sort" name="sort" class="form-select">

                                    <option value="featured" @selected(request('sort', 'featured') === 'featured')>
                                        Featured
                                    </option>

                                    <option value="name_asc" @selected(request('sort') === 'name_asc')>
                                        Name: A-Z
                                    </option>

                                    <option value="name_desc" @selected(request('sort') === 'name_desc')>
                                        Name: Z-A
                                    </option>

                                </select>

                            </div>


                            {{-- Filter Button --}}
                            <div class="col-md-2">

                                <button type="submit" class="btn btn-storefront-primary w-100">
                                    Apply
                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>


            {{-- Product Count --}}
            <div class="d-flex justify-content-between align-items-center mb-4">

                <p class="text-muted mb-0">

                    Showing
                    <strong>{{ $products->count() }}</strong>
                    of
                    <strong>{{ $products->total() }}</strong>
                    products

                </p>

            </div>


            {{-- Products --}}
            @if ($products->count())

                <div class="row g-4">

                    @forelse ($products as $product)
                        <div class="col-12 col-sm-6 col-lg-4 col-xl-3">

                            <x-storefront.product-card :product="$product" />

                        </div>

                    @empty

                        <div class="col-12">

                            <div class="storefront-shop-empty text-center">

                                <div class="storefront-shop-empty-icon">
                                    <i class="bi bi-box-seam"></i>
                                </div>

                                <h3>
                                    No products found
                                </h3>

                                <p>
                                    We couldn't find any dry fruits matching your search.
                                </p>

                                <a href="{{ route('storefront.shop') }}" class="btn btn-storefront-primary">
                                    View All Products
                                </a>

                            </div>

                        </div>
                    @endforelse

                </div>


                {{-- Pagination --}}
                <div class="d-flex justify-content-center mt-5">

                    {{ $products->links() }}

                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-5">

                    <div class="mb-3">

                        <i class="bi bi-search fs-1 text-muted"></i>

                    </div>

                    <h3>
                        No products found
                    </h3>

                    <p class="text-muted">
                        Try changing your search or category filters.
                    </p>

                    <a href="{{ route('storefront.shop') }}" class="btn btn-storefront-primary">
                        View All Products
                    </a>

                </div>

            @endif

        </div>

    </section>

@endsection
