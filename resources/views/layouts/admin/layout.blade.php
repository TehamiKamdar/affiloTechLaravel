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
    @include('layouts.admin.components.head')
    @yield('styles')
</head>

<body class="">
    <div class="loader-container" id="chart-loader" style="display: none;">
        <div class="loading-overlay">
            <div class="loading-spinner"></div>
            <div class="loading-text">Loading data...</div>
        </div>
    </div>
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg navbar-light sticky-top">
                <div class="w-100 d-flex justify-content-between">
                    <ul class="navbar-nav mr-3">
                        <div class="menu-box"><a href="#" data-toggle="sidebar" class="bg-white rounded-circle nav-link nav-link-lg
                                            collapse-btn"> <i data-feather="align-justify"></i></a></div>
                    </ul>
                    {{-- Main Navbar --}}
                    @yield('breadcrumb')

                    <div class="logout-box">
                        <form id="logoutform" style="height: 44px" action="{{ route('logout') }}" method="POST" class="display-hidden">
                            <button type="submit" href="" class="border-0 bg-white rounded-circle nav-link nav-link-lg collapse-btn" title="Logout"><i data-feather="power" class="text-danger"></i>
                                {{ csrf_field() }}
                            </button>
                        </form>
                    </div>
                </div>
            </nav>
            {{-- Aside Navbar --}}
            @include('layouts.admin.components.aside')

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.1/daterangepicker.min.js"></script>

@yield('scripts')
