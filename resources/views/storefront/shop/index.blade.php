@extends('layouts.storefront.app')

@section('title', 'Shop - ' . config('app.name'))

@section('content')

    <div class="storefront-shop-page">

        {{-- =========================================================
        Shop Header
    ========================================================== --}}

        <section class="storefront-shop-header">

            <div class="container">

                <div class="storefront-shop-header-content">

                    <span class="storefront-section-eyebrow">
                        Our Collection
                    </span>

                    <h1 class="storefront-shop-title">
                        Shop Premium Dry Fruits
                    </h1>

                    <p class="storefront-shop-description">
                        Discover premium quality dry fruits carefully selected
                        for freshness, taste and everyday enjoyment.
                    </p>

                </div>

            </div>

        </section>


        {{-- =========================================================
        Shop Content
    ========================================================== --}}

        <section class="storefront-shop-content">

            <div class="container">

                <div class="row g-4">


                    {{-- =================================================
                    Sidebar
                ================================================== --}}

                    <div class="col-lg-3">

                        <div class="storefront-shop-sidebar">


                            {{-- Filter Heading --}}

                            <div class="d-flex justify-content-between align-items-center mb-4">

                                <h5 class="mb-0 fw-bold">
                                    Filters
                                </h5>

                                <a href="{{ route('storefront.shop') }}" class="storefront-filter-clear">

                                    Clear All

                                </a>

                            </div>


                            {{-- Search --}}

                            <div class="storefront-filter-group">

                                <h6 class="storefront-filter-title">
                                    Search
                                </h6>

                                <form method="GET" action="{{ route('storefront.shop') }}">

                                    <div class="input-group">

                                        <input type="search" name="search" value="{{ request('search') }}"
                                            class="form-control" placeholder="Search products...">

                                        <button type="submit" class="btn btn-storefront-primary">

                                            <i class="bi bi-search"></i>

                                        </button>

                                    </div>

                                </form>

                            </div>


                            {{-- Categories --}}

                            <div class="storefront-filter-group">

                                <h6 class="storefront-filter-title">
                                    Categories
                                </h6>

                                <div class="storefront-filter-list">

                                    <a href="{{ route('storefront.shop', request()->except('category', 'page')) }}"
                                        class="{{ !request('category') ? 'active' : '' }}">

                                        <span>
                                            All Products
                                        </span>

                                    </a>


                                    @foreach ($categories as $category)
                                        <a href="{{ route('storefront.shop', array_merge(request()->except('page'), ['category' => $category->slug])) }}"
                                            class="{{ request('category') === $category->slug ? 'active' : '' }}">

                                            <span>
                                                {{ $category->name }}
                                            </span>

                                            <i class="bi bi-chevron-right"></i>

                                        </a>
                                    @endforeach

                                </div>

                            </div>


                            {{-- Price Filter --}}

                            <div class="storefront-filter-group">

                                <h6 class="storefront-filter-title">
                                    Price Range
                                </h6>


                                <form method="GET" action="{{ route('storefront.shop') }}">

                                    @if (request('search'))
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                    @endif


                                    @if (request('category'))
                                        <input type="hidden" name="category" value="{{ request('category') }}">
                                    @endif


                                    @if (request('sort'))
                                        <input type="hidden" name="sort" value="{{ request('sort') }}">
                                    @endif


                                    <div class="row g-2">

                                        <div class="col-6">

                                            <label class="form-label small">
                                                Min
                                            </label>

                                            <input type="number" name="min_price" value="{{ request('min_price') }}"
                                                min="0" step="1" class="form-control" placeholder="Rs. 0">

                                        </div>


                                        <div class="col-6">

                                            <label class="form-label small">
                                                Max
                                            </label>

                                            <input type="number" name="max_price" value="{{ request('max_price') }}"
                                                min="0" step="1" class="form-control" placeholder="Rs. 5000">

                                        </div>

                                    </div>


                                    <button type="submit" class="btn btn-storefront-primary w-100 mt-3">

                                        Apply Price

                                    </button>

                                </form>

                            </div>


                            {{-- Featured --}}

                            <div class="storefront-filter-group">

                                <h6 class="storefront-filter-title">
                                    Quick Filters
                                </h6>

                                <a href="{{ route('storefront.shop', array_merge(request()->except('page'), ['sort' => 'featured'])) }}"
                                    class="storefront-quick-filter">

                                    <span>
                                        <i class="bi bi-star me-2"></i>
                                        Featured Products
                                    </span>

                                    <i class="bi bi-chevron-right"></i>

                                </a>

                            </div>

                        </div>

                    </div>


                    {{-- =================================================
                    Products
                ================================================== --}}

                    <div class="col-lg-9">


                        {{-- Toolbar --}}

                        <div class="storefront-shop-toolbar mb-4">

                            <div>

                                <p class="mb-0 text-muted">

                                    Showing

                                    <strong>
                                        {{ $products->firstItem() ?? 0 }}
                                    </strong>

                                    -

                                    <strong>
                                        {{ $products->lastItem() ?? 0 }}
                                    </strong>

                                    of

                                    <strong>
                                        {{ $products->total() }}
                                    </strong>

                                    products

                                </p>

                            </div>


                            {{-- Sorting --}}

                            <form method="GET" action="{{ route('storefront.shop') }}" class="storefront-sort-form">

                                @if (request('search'))
                                    <input type="hidden" name="search" value="{{ request('search') }}">
                                @endif


                                @if (request('category'))
                                    <input type="hidden" name="category" value="{{ request('category') }}">
                                @endif


                                @if (request('min_price'))
                                    <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                                @endif


                                @if (request('max_price'))
                                    <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                                @endif


                                <label for="sort" class="visually-hidden">

                                    Sort Products

                                </label>


                                <div class="shop-sort">

                                    <label for="shopSort" class="form-label mb-1">
                                        Sort By
                                    </label>

                                    <select id="shopSort" name="sort" class="form-select"
                                        onchange="this.form.submit()">

                                        <option value="featured" @selected(request('sort', 'featured') === 'featured')>
                                            Featured
                                        </option>

                                        <option value="name_asc" @selected(request('sort') === 'name_asc')>
                                            Name: A–Z
                                        </option>

                                        <option value="name_desc" @selected(request('sort') === 'name_desc')>
                                            Name: Z–A
                                        </option>

                                        <option value="price_asc" @selected(request('sort') === 'price_asc')>
                                            Price: Low to High
                                        </option>

                                        <option value="price_desc" @selected(request('sort') === 'price_desc')>
                                            Price: High to Low
                                        </option>

                                    </select>

                                </div>
                                <div class="shop-filter-group">

                                    <label for="availability" class="form-label">
                                        Availability
                                    </label>

                                    <select id="availability" name="availability" class="form-select"
                                        onchange="this.form.submit()">

                                        <option value="" @selected(!request('availability'))>
                                            All Products
                                        </option>

                                        <option value="in_stock" @selected(request('availability') === 'in_stock')>
                                            In Stock
                                        </option>

                                        <option value="out_of_stock" @selected(request('availability') === 'out_of_stock')>
                                            Out of Stock
                                        </option>

                                    </select>

                                </div>

                            </form>

                        </div>


                        {{-- Active Filters --}}

                        @if (request()->filled('search') ||
                                request()->filled('category') ||
                                request()->filled('min_price') ||
                                request()->filled('max_price'))

                            <div class="storefront-active-filters mb-4">

                                <span class="storefront-active-filter-label">
                                    Active filters:
                                </span>


                                @if (request('search'))
                                    <span class="storefront-filter-badge">

                                        Search:
                                        {{ request('search') }}

                                    </span>
                                @endif


                                @if (request('category'))
                                    <span class="storefront-filter-badge">

                                        Category:
                                        {{ $categories->firstWhere('slug', request('category'))?->name }}

                                    </span>
                                @endif


                                @if (request('min_price'))
                                    <span class="storefront-filter-badge">

                                        Min:
                                        Rs. {{ number_format(request('min_price')) }}

                                    </span>
                                @endif


                                @if (request('max_price'))
                                    <span class="storefront-filter-badge">

                                        Max:
                                        Rs. {{ number_format(request('max_price')) }}

                                    </span>
                                @endif

                            </div>

                        @endif
                        @if (request()->filled('search') ||
                                request()->filled('category') ||
                                request()->filled('availability') ||
                                request()->filled('sort'))
                            <a href="{{ route('storefront.shop') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle me-1"></i>
                                Clear Filters
                            </a>
                        @endif


                        {{-- Product Grid --}}

                        @if ($products->isNotEmpty())

                            <div class="row g-4">

                                @foreach ($products as $product)
                                    <div class="col-6 col-md-4">

                                        <x-storefront.product-card :product="$product" />

                                    </div>
                                @endforeach

                            </div>


                            {{-- Pagination --}}

                            <div class="storefront-pagination mt-5">

                                {{ $products->links() }}

                            </div>
                        @else
                            {{-- Empty State --}}

                            <div class="storefront-shop-empty text-center">

                                <div class="storefront-shop-empty-icon">

                                    <i class="bi bi-search"></i>

                                </div>


                                <h3>
                                    No products found
                                </h3>


                                <p class="text-muted">

                                    We couldn't find any products matching
                                    your current filters.

                                </p>


                                <a href="{{ route('storefront.shop') }}" class="btn btn-storefront-primary">

                                    View All Products

                                </a>

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </section>

    </div>

@endsection
