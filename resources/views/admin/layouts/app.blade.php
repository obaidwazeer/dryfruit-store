<!doctype html>
<html lang="en" data-bs-theme="light">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title', config('app.name') . ' Admin')
    </title>

    <link rel="icon" href="{{ asset('assets/admin/images/favicon-32x32.png') }}" type="image/png">

    {{-- Bootstrap --}}
    <link href="{{ asset('assets/admin/css/bootstrap.min.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/admin/css/bootstrap-extended.css') }}" rel="stylesheet">

    {{-- Admin Theme --}}
    <link href="{{ asset('assets/admin/css/app.css') }}" rel="stylesheet">

    {{-- Icons --}}
    <link href="{{ asset('assets/admin/css/icons.css') }}" rel="stylesheet">

    {{-- Plugin CSS --}}
    <link href="{{ asset('assets/admin/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet">

    <link href="{{ asset('assets/admin/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet">

    {{-- Our custom CSS MUST be last --}}
    <link href="{{ asset('assets/admin/css/custom-admin.css') }}" rel="stylesheet">

    @stack('styles')


</head>


<body>

    <div class="wrapper">

        {{-- Sidebar --}}
        @include('admin.components.sidebar')


        {{-- Header --}}
        @include('admin.components.topbar')


        {{-- Main Content --}}
        <div class="page-wrapper">

            <div class="page-content">

                {{-- Flash Messages --}}
                @include('admin.components.alerts')

                @yield('content')

            </div>

        </div>


        {{-- Overlay --}}
        <div class="overlay toggle-icon"></div>


        {{-- Back To Top --}}
        <a href="javascript:;" class="back-to-top">

            <i class="bx bxs-up-arrow-alt"></i>

        </a>


        {{-- Footer --}}
        <footer class="page-footer">

            <p class="mb-0">
                © {{ date('Y') }}
                {{ config('app.name') }}.
                All rights reserved.
            </p>

        </footer>

    </div>


    {{-- Bootstrap --}}
    <script src="{{ asset('assets/admin/js/bootstrap.bundle.min.js') }}"></script>

    {{-- jQuery --}}
    <script src="{{ asset('assets/admin/js/jquery.min.js') }}"></script>

    {{-- SimpleBar --}}
    <script src="{{ asset('assets/admin/plugins/simplebar/js/simplebar.min.js') }}"></script>

    {{-- MetisMenu --}}
    <script src="{{ asset('assets/admin/plugins/metismenu/js/metisMenu.min.js') }}"></script>

    {{-- Perfect Scrollbar --}}
    <script src="{{ asset('assets/admin/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>

    {{-- Admin Theme --}}
    <script src="{{ asset('assets/admin/js/app.js') }}"></script>

    @stack('scripts')

</body>

</html>
