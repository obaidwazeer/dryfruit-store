{{-- =========================================================
    Storefront Announcement Bar
========================================================== --}}

<div class="storefront-announcement">

    <div class="container">

        <div class="d-flex justify-content-center align-items-center">

            <span>
                Premium Quality Dry Fruits • Freshly Packed • Nationwide Delivery
            </span>

        </div>

    </div>

</div>



{{-- =========================================================
    Main Header
========================================================== --}}

<div class="storefront-main-header">

    <div class="container">

        <div class="d-flex align-items-center justify-content-between gap-3">


            {{-- =================================================
                Logo
            ================================================== --}}

            <a href="{{ route('storefront.home') }}" class="storefront-logo text-decoration-none"
                aria-label="{{ config('app.name') }} Home">

                <span class="storefront-logo-icon">

                    <i class="bi bi-basket2-fill"></i>

                </span>


                <span class="storefront-logo-text">

                    {{ config('app.name') }}

                </span>

            </a>


            {{-- =================================================
                Desktop Search
            ================================================== --}}

            <form method="GET" action="{{ route('storefront.shop') }}" class="storefront-search d-none d-lg-flex"
                role="search">

                <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Search dry fruits..." aria-label="Search products" autocomplete="off">


                <button type="submit" class="storefront-search-button" aria-label="Search">

                    <i class="bi bi-search"></i>

                </button>

            </form>


            {{-- =================================================
                Header Actions
            ================================================== --}}

            <div class="storefront-header-actions">


                {{-- ---------------------------------------------
                    Mobile Search
                ---------------------------------------------- --}}

                <button type="button" class="storefront-header-icon d-lg-none" data-bs-toggle="collapse"
                    data-bs-target="#mobileSearch" aria-controls="mobileSearch" aria-expanded="false"
                    aria-label="Search">

                    <i class="bi bi-search"></i>

                </button>


                {{-- ---------------------------------------------
                    Cart
                ---------------------------------------------- --}}

                <a href="{{ route('storefront.cart.index') }}" class="storefront-header-icon position-relative"
                    aria-label="Shopping cart">

                    <i class="bi bi-bag"></i>


                    @if ($cartCount > 0)
                        <span class="storefront-cart-count">

                            {{ $cartCount > 99 ? '99+' : $cartCount }}

                        </span>
                    @endif

                </a>


                {{-- ---------------------------------------------
                    Mobile Menu
                ---------------------------------------------- --}}

                <button type="button" class="storefront-header-icon d-lg-none" data-bs-toggle="offcanvas"
                    data-bs-target="#storefrontMobileMenu" aria-controls="storefrontMobileMenu" aria-label="Open menu">

                    <i class="bi bi-list"></i>

                </button>

            </div>

        </div>


        {{-- =================================================
            Mobile Search
        ================================================== --}}

        <div class="collapse d-lg-none" id="mobileSearch">

            <form method="GET" action="{{ route('storefront.shop') }}" class="storefront-mobile-search mt-3 pb-3">

                <div class="input-group">

                    <input type="search" name="search" value="{{ request('search') }}" class="form-control"
                        placeholder="Search dry fruits..." aria-label="Search products" autocomplete="off">


                    <button type="submit" class="btn btn-storefront-primary">

                        <i class="bi bi-search"></i>

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



{{-- =========================================================
    Desktop Navigation
========================================================== --}}

<nav class="storefront-navigation d-none d-lg-block" aria-label="Main navigation">

    <div class="container">

        <div class="storefront-nav-inner">


            {{-- =================================================
                Navigation Links
            ================================================== --}}

            <ul class="storefront-nav-list">


                {{-- ---------------------------------------------
                    Home
                ---------------------------------------------- --}}

                <li>

                    <a href="{{ route('storefront.home') }}"
                        class="{{ request()->routeIs('storefront.home') ? 'active' : '' }}">

                        <i class="bi bi-house me-1"></i>

                        Home

                    </a>

                </li>


                {{-- ---------------------------------------------
                    Shop
                ---------------------------------------------- --}}

                <li>

                    <a href="{{ route('storefront.shop') }}"
                        class="{{ request()->routeIs('storefront.shop') && !request('category') && !request('sort') ? 'active' : '' }}">

                        <i class="bi bi-grid me-1"></i>

                        Shop

                    </a>

                </li>


                {{-- =================================================
                    Categories Dropdown
                ================================================== --}}

                <li class="dropdown">


                    <a href="#"
                        class="dropdown-toggle
                            {{ request()->routeIs('storefront.categories.show') ? 'active' : '' }}"
                        role="button" data-bs-toggle="dropdown" aria-expanded="false">

                        <i class="bi bi-tags me-1"></i>

                        Categories

                    </a>


                    <ul class="dropdown-menu storefront-category-dropdown">


                        {{-- -----------------------------------------
                            All Categories
                        ------------------------------------------ --}}

                        <li>

                            <a class="dropdown-item" href="{{ route('storefront.shop') }}">

                                <i class="bi bi-grid-3x3-gap me-2"></i>

                                All Categories

                            </a>

                        </li>


                        <li>
                            <hr class="dropdown-divider">
                        </li>


                        {{-- -----------------------------------------
                            Dynamic Categories
                        ------------------------------------------ --}}

                        @forelse ($categories as $category)
                            <li>

                                <a class="dropdown-item
                                        {{ request()->routeIs('storefront.categories.show') && request()->route('category')?->slug === $category->slug
                                            ? 'active'
                                            : '' }}"
                                    href="{{ route('storefront.categories.show', $category->slug) }}">

                                    <i class="bi bi-chevron-right me-2"></i>

                                    {{ $category->name }}

                                </a>

                            </li>

                        @empty

                            <li>

                                <span class="dropdown-item-text text-muted">

                                    Categories coming soon

                                </span>

                            </li>
                        @endforelse

                    </ul>

                </li>


                {{-- ---------------------------------------------
                    Featured Products
                ---------------------------------------------- --}}

                <li>

                    <a href="{{ route('storefront.shop', ['sort' => 'featured']) }}"
                        class="{{ request()->routeIs('storefront.shop') && request('sort') === 'featured' ? 'active' : '' }}">

                        <i class="bi bi-star me-1"></i>

                        Featured

                    </a>

                </li>


                {{-- ---------------------------------------------
                    All Products
                ---------------------------------------------- --}}

                <li>

                    <a href="{{ route('storefront.shop', ['sort' => 'name_asc']) }}"
                        class="{{ request()->routeIs('storefront.shop') && request('sort') === 'name_asc' ? 'active' : '' }}">

                        <i class="bi bi-box-seam me-1"></i>

                        All Products

                    </a>

                </li>

            </ul>


            {{-- =================================================
                Navigation Cart
            ================================================== --}}

            <a href="{{ route('storefront.cart.index') }}" class="storefront-nav-cart" aria-label="Shopping cart">

                <i class="bi bi-bag me-1"></i>

                Cart

                @if ($cartCount > 0)
                    <span class="ms-1">

                        ({{ $cartCount > 99 ? '99+' : $cartCount }})

                    </span>
                @endif

            </a>

        </div>

    </div>

</nav>



{{-- =========================================================
    Mobile Menu
========================================================== --}}

<div class="offcanvas offcanvas-start" tabindex="-1" id="storefrontMobileMenu"
    aria-labelledby="storefrontMobileMenuLabel">


    {{-- =================================================
        Mobile Menu Header
    ================================================== --}}

    <div class="offcanvas-header">

        <h5 class="offcanvas-title" id="storefrontMobileMenuLabel">

            {{ config('app.name') }}

        </h5>


        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close">

        </button>

    </div>


    {{-- =================================================
        Mobile Menu Body
    ================================================== --}}

    <div class="offcanvas-body">

        <nav aria-label="Mobile navigation">

            <ul class="list-unstyled storefront-mobile-nav">


                {{-- ---------------------------------------------
                    Home
                ---------------------------------------------- --}}

                <li>

                    <a href="{{ route('storefront.home') }}"
                        class="{{ request()->routeIs('storefront.home') ? 'active' : '' }}">

                        <i class="bi bi-house me-2"></i>

                        Home

                    </a>

                </li>


                {{-- ---------------------------------------------
                    Shop
                ---------------------------------------------- --}}

                <li>

                    <a href="{{ route('storefront.shop') }}"
                        class="{{ request()->routeIs('storefront.shop') && !request('category') && !request('sort') ? 'active' : '' }}">

                        <i class="bi bi-grid me-2"></i>

                        Shop

                    </a>

                </li>


                {{-- =================================================
                    Mobile Categories
                ================================================== --}}

                <li>


                    <button type="button" class="storefront-mobile-nav-toggle" data-bs-toggle="collapse"
                        data-bs-target="#mobileCategories" aria-controls="mobileCategories"
                        aria-expanded="{{ request()->routeIs('storefront.categories.show') ? 'true' : 'false' }}">

                        <span>

                            <i class="bi bi-tags me-2"></i>

                            Categories

                        </span>


                        <i class="bi bi-chevron-down"></i>

                    </button>


                    <div class="collapse {{ request()->routeIs('storefront.categories.show') ? 'show' : '' }}"
                        id="mobileCategories">


                        <ul class="list-unstyled storefront-mobile-category-list">


                            {{-- -----------------------------------------
                                All Categories
                            ------------------------------------------ --}}

                            <li>

                                <a href="{{ route('storefront.shop') }}"
                                    class="{{ request()->routeIs('storefront.shop') && !request('category') ? 'active' : '' }}">

                                    <i class="bi bi-grid-3x3-gap me-2"></i>

                                    All Categories

                                </a>

                            </li>


                            {{-- -----------------------------------------
                                Dynamic Categories
                            ------------------------------------------ --}}

                            @forelse ($categories as $category)
                                <li>

                                    <a href="{{ route('storefront.categories.show', $category->slug) }}"
                                        class="{{ request()->routeIs('storefront.categories.show') && request()->route('category')?->slug === $category->slug
                                            ? 'active'
                                            : '' }}">

                                        <i class="bi bi-chevron-right me-2"></i>

                                        {{ $category->name }}

                                    </a>

                                </li>

                            @empty

                                <li>

                                    <span class="text-muted small">

                                        Categories coming soon

                                    </span>

                                </li>
                            @endforelse

                        </ul>

                    </div>

                </li>


                {{-- ---------------------------------------------
                    Featured Products
                ---------------------------------------------- --}}

                <li>

                    <a href="{{ route('storefront.shop', ['sort' => 'featured']) }}"
                        class="{{ request()->routeIs('storefront.shop') && request('sort') === 'featured' ? 'active' : '' }}">

                        <i class="bi bi-star me-2"></i>

                        Featured Products

                    </a>

                </li>


                {{-- ---------------------------------------------
                    All Products
                ---------------------------------------------- --}}

                <li>

                    <a href="{{ route('storefront.shop', ['sort' => 'name_asc']) }}"
                        class="{{ request()->routeIs('storefront.shop') && request('sort') === 'name_asc' ? 'active' : '' }}">

                        <i class="bi bi-box-seam me-2"></i>

                        All Products

                    </a>

                </li>


                {{-- ---------------------------------------------
                    Cart
                ---------------------------------------------- --}}

                <li>

                    <a href="{{ route('storefront.cart.index') }}">

                        <i class="bi bi-bag me-2"></i>

                        Cart

                        @if ($cartCount > 0)
                            <span class="badge bg-secondary ms-2">

                                {{ $cartCount > 99 ? '99+' : $cartCount }}

                            </span>
                        @endif

                    </a>

                </li>

            </ul>

        </nav>


        {{-- =================================================
            Mobile Menu Footer
        ================================================== --}}

        <div class="mt-4 pt-4 border-top">

            <div class="small text-muted">

                <div class="mb-2">

                    <i class="bi bi-shield-check me-2"></i>

                    Secure Shopping

                </div>


                <div class="mb-2">

                    <i class="bi bi-truck me-2"></i>

                    Nationwide Delivery

                </div>


                <div>

                    <i class="bi bi-check-circle me-2"></i>

                    Premium Quality

                </div>

            </div>

        </div>

    </div>

</div>
