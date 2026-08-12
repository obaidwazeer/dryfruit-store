<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', config('app.name', 'Dry Fruit Store'))
    </title>

    <meta name="description" content="@yield('meta_description', 'Premium quality dry fruits delivered to your doorstep.')">

    {{-- Google Font used by the original theme --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">

    {{-- Swiper --}}
    <link rel="stylesheet" href="{{ asset('assets/storefront/plugins/swiper/css/swiper-bundle.min.css') }}">

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="{{ asset('assets/storefront/css/bootstrap.min.css') }}">

    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="{{ asset('assets/storefront/css/bootstrap-icons.min.css') }}">

    {{-- Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/storefront/css/style.css') }}">

    {{-- Price range CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/storefront/css/price_range_style.css') }}">

    {{-- Our dry-fruit customizations --}}
    <link rel="stylesheet" href="{{ asset('assets/storefront/css/dryfruit.css') }}">

    @vite(['resources/css/storefront.css'])
    {{-- Page-specific styles --}}
    @stack('styles')
</head>

<body>

    {{-- Storefront Header --}}
    @include('components.storefront.header')

    {{-- Main Page Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Storefront Footer --}}
    @include('components.storefront.footer')


    {{-- jQuery --}}
    <script src="{{ asset('assets/storefront/js/jquery.min.js') }}"></script>

    {{-- Bootstrap --}}
    <script src="{{ asset('assets/storefront/js/bootstrap.bundle.min.js') }}"></script>

    {{-- Swiper --}}
    <script src="{{ asset('assets/storefront/plugins/swiper/js/swiper-bundle.min.js') }}"></script>

    {{-- Theme JavaScript --}}
    <script src="{{ asset('assets/storefront/js/search-slider.js') }}"></script>

    <script src="{{ asset('assets/storefront/js/index.js') }}"></script>

    <script src="{{ asset('assets/storefront/js/product-details.js') }}"></script>

    <script src="{{ asset('assets/storefront/js/main.js') }}"></script>

    {{-- Page-specific scripts --}}
    @stack('scripts')

</body>

</html>
