<div class="sidebar-wrapper" data-simplebar="true">

    <div class="sidebar-header">

        <div>
            <img src="{{ asset('assets/admin/images/logo-icon.png') }}" class="logo-icon" alt="{{ config('app.name') }}">
        </div>

        <div>
            <h4 class="logo-text">
                {{ config('app.name') }}
            </h4>
        </div>

        <div class="mobile-toggle-icon ms-auto">
            <i class="bx bx-x"></i>
        </div>

    </div>


    <ul class="metismenu" id="menu">

        {{-- Dashboard --}}
        @can('dashboard.view')
            <li>

                <a href="{{ route('admin.dashboard') }}">

                    <div class="parent-icon">
                        <i class="bx bx-home-alt"></i>
                    </div>

                    <div class="menu-title">
                        Dashboard
                    </div>

                </a>

            </li>
        @endcan


        {{-- Catalog --}}
        @canany(['categories.view', 'products.view'])

            <li>

                <a href="javascript:;" class="has-arrow">

                    <div class="parent-icon">
                        <i class="bx bx-cart"></i>
                    </div>

                    <div class="menu-title">
                        Catalog
                    </div>

                </a>


                <ul>

                    @can('categories.view')
                        <li>

                            <a href="{{ route('admin.categories.index') }}">

                                <i class="bx bx-radio-circle"></i>

                                Categories

                            </a>

                        </li>
                    @endcan


                    @can('products.view')
                        <li>

                            <a href="{{ route('admin.products.index') }}">

                                <i class="bx bx-radio-circle"></i>

                                Products

                            </a>

                        </li>
                    @endcan

                </ul>

            </li>

        @endcanany


        {{-- Orders --}}
        <li>
            <a href="{{ route('admin.admin.orders.index') }}">

                <div class="parent-icon">
                    <i class="bx bx-cart"></i>
                </div>

                <div class="menu-title">
                    Orders
                </div>

            </a>
        </li>

        @can('shipping.view')
            <li class="nav-item">
                <a href="{{ route('admin.shipping.index') }}"
                    class="nav-link {{ request()->routeIs('admin.shipping.*') ? 'active' : '' }}">

                    {{-- <i class="bi bi-truck"></i> --}}
                    <div class="parent-icon">
                        <i class="bx bxs-truck"></i>
                    </div>

                    <span>
                        Shipping Rates
                    </span>

                </a>
            </li>
        @endcan

        {{-- Customers --}}
        @if (auth()->user()->can('customers.view'))
            <li>

                <a href="{{ route('admin.customers.index') }}">

                    <div class="parent-icon">
                        <i class="bx bx-user"></i>
                    </div>

                    <div class="menu-title">
                        Customers
                    </div>

                </a>

            </li>
        @endif


        {{-- Settings --}}
        <li>

            <a href="javascript:;">

                <div class="parent-icon">
                    <i class="bx bx-cog"></i>
                </div>

                <div class="menu-title">
                    Settings
                </div>

            </a>

        </li>


        {{-- Logout --}}
        <li class="mt-3">

            <form method="POST" action="{{ route('admin.logout') }}">

                @csrf

                <button type="submit" class="btn btn-link text-start text-white text-decoration-none w-100">

                    <div class="d-flex align-items-center">

                        <div class="parent-icon">
                            <i class="bx bx-log-out"></i>
                        </div>

                        <div class="menu-title">
                            Logout
                        </div>

                    </div>

                </button>

            </form>

        </li>

    </ul>

</div>
