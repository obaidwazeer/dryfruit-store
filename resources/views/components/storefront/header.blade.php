<header>

    {{-- =========================================================
        Top Information Bar
    ========================================================== --}}
    <div class="top-header py-2">
        <div class="container px-3">

            <div class="d-flex align-items-center justify-content-between">

                <div class="d-none d-lg-flex align-items-center gap-4">

                    <span class="small">
                        Fresh & Premium Dry Fruits
                    </span>

                    <span class="small">
                        Nationwide Delivery
                    </span>

                </div>

                <div class="ms-auto d-flex align-items-center gap-3">

                    <a href="#"
                       class="text-decoration-none small">
                        Track Order
                    </a>

                    <a href="#"
                       class="text-decoration-none small">
                        Contact Us
                    </a>

                </div>

            </div>

        </div>
    </div>


    {{-- =========================================================
        Main Navigation
    ========================================================== --}}
    <nav class="navbar navbar-expand-xl border-bottom py-3">

        <div class="container px-3">


            {{-- =================================================
                Brand / Logo
            ================================================== --}}
            <a
                class="navbar-brand d-flex align-items-center"
                href="{{ route('storefront.home') }}"
            >

                {{-- Temporary text logo.
                     We will replace this with the client's
                     actual logo later. --}}

                <span class="fw-bold fs-4">
                    Dry Fruit Store
                </span>

            </a>


            {{-- =================================================
                Mobile Menu Button
            ================================================== --}}
            <button
                type="button"
                class="d-xl-none btn-menu-close"
                data-bs-toggle="offcanvas"
                data-bs-target="#storefrontNavigation"
                aria-controls="storefrontNavigation"
                aria-label="Open navigation menu"
            >
                <i class="bi bi-list"></i>
            </button>


            {{-- =================================================
                Navigation Offcanvas
            ================================================== --}}
            <div
                class="offcanvas offcanvas-start"
                tabindex="-1"
                id="storefrontNavigation"
                aria-labelledby="storefrontNavigationLabel"
            >

                {{-- Mobile Header --}}
                <div class="offcanvas-header">

                    <span
                        class="fw-bold fs-5"
                        id="storefrontNavigationLabel"
                    >
                        Dry Fruit Store
                    </span>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="offcanvas"
                        aria-label="Close"
                    ></button>

                </div>


                {{-- Mobile Search --}}
                <div class="nav-search p-3 pt-0 border-bottom d-flex d-xl-none">

                    <form
                        class="position-relative w-100"
                        method="GET"
                        action="#"
                    >

                        <input
                            type="search"
                            name="q"
                            class="form-control nav-search-control ps-5 border-0 py-2"
                            placeholder="Search dry fruits..."
                            aria-label="Search products"
                        >

                        <span
                            class="position-absolute top-50 start-0 translate-middle-y"
                        >
                            <i class="bi bi-search fs-6 ms-3"></i>
                        </span>

                    </form>

                </div>


                {{-- Navigation Links --}}
                <div class="offcanvas-body p-0">

                    <ul class="navbar-nav mx-auto gap-0 gap-xl-2">


                        {{-- Home --}}
                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="{{ route('storefront.home') }}"
                            >
                                <span class="parent-menu-name">
                                    Home
                                </span>
                            </a>

                        </li>


                        {{-- Shop --}}
                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="#"
                            >
                                <span class="parent-menu-name">
                                    Shop
                                </span>
                            </a>

                        </li>


                        {{-- Categories --}}
                        <li class="nav-item dropdown">

                            <a
                                class="nav-link dropdown-toggle dropdown-toggle-nocaret"
                                href="#"
                                data-bs-toggle="dropdown"
                                aria-expanded="false"
                            >

                                <span class="parent-menu-name">
                                    Categories
                                </span>

                                <span class="parent-menu-icon ms-2">
                                    <i class="bi bi-chevron-down"></i>
                                </span>

                            </a>


                            <ul class="dropdown-menu">

                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        Almonds
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        Cashews
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        Pistachios
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        Walnuts
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        Dates
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        Raisins
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="dropdown-item"
                                        href="#"
                                    >
                                        Mixed Dry Fruits
                                    </a>
                                </li>

                            </ul>

                        </li>


                        {{-- Offers --}}
                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="#"
                            >
                                <span class="parent-menu-name">
                                    Offers
                                </span>
                            </a>

                        </li>


                        {{-- About --}}
                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="#"
                            >
                                <span class="parent-menu-name">
                                    About Us
                                </span>
                            </a>

                        </li>


                        {{-- Contact --}}
                        <li class="nav-item">

                            <a
                                class="nav-link"
                                href="#"
                            >
                                <span class="parent-menu-name">
                                    Contact
                                </span>
                            </a>

                        </li>

                    </ul>

                </div>

            </div>


            {{-- =================================================
                Right Side Actions
            ================================================== --}}
            <div class="right-links nav gap-2 d-flex align-items-center">


                {{-- Search --}}
                <a
                    class="nav-link"
                    href="#"
                    aria-label="Search"
                    title="Search"
                >
                    <i class="bi bi-search"></i>
                </a>


                {{-- Customer Account --}}
                <a
                    class="nav-link"
                    href="#"
                    aria-label="My Account"
                    title="My Account"
                >
                    <i class="bi bi-person-circle"></i>
                </a>


                {{-- Wishlist --}}
                <a
                    class="nav-link position-relative"
                    href="#"
                    aria-label="Wishlist"
                    title="Wishlist"
                >

                    <i class="bi bi-heart"></i>

                    <span class="notify-badge">
                        0
                    </span>

                </a>


                {{-- Cart --}}
                <a
                    class="nav-link position-relative"
                    href="#"
                    aria-label="Shopping Cart"
                    title="Shopping Cart"
                >

                    <i class="bi bi-basket2"></i>

                    <span class="notify-badge">
                        0
                    </span>

                </a>

            </div>

        </div>

    </nav>

</header>
