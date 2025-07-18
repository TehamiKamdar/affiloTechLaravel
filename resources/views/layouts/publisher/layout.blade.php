<!--
=========================================================
* Soft UI Dashboard 3 - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
@include('layouts.publisher.components.head')
@yield('styles')
</head>

<body class="">
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>

            {{-- Main Navbar --}}
            @yield('navbar')
            {{-- Aside Navbar --}}
            @include('layouts.publisher.components.aside')

            {{-- Main Content --}}
            <div class="main-content">

            @yield('content')

            </div>
            {{-- Main Content End --}}


        </div>
    </div>

</body>

</html>

<!-- General JS Scripts -->
<script src="{{asset('publisherAssets/assets/js/app.min.js')}}"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- Template JS File -->
<script src="{{asset('publisherAssets/assets/js/scripts.js')}}"></script>
<!-- Custom JS File -->
<script src="{{asset('publisherAssets/assets/js/custom.js')}}"></script>
@yield('scripts')
