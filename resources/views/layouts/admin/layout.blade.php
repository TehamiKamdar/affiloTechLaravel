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

{{-- Head Tag Including CSS and FontIcons --}}
@include('layouts.admin.components.head')

{{-- Custom Stylings (if Any) --}}
@yield('styles')

<body class="g-sidenav-show  bg-gray-100">
    @include('layouts.admin.components.aside')
    <main class="main-content position-relative max-height-vh-100 h-100  ">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl d-xl-none" id="navbarBlur" navbar-scroll="true">
            <div class="container-fluid py-1 px-3 d-flex justify-content-end align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0 d-flex align-items-center" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </a>
            </div>
        </nav>
        {{-- @include('layouts.admin.components.navbar') --}}

        <div class="container-fluid py-4">
            @yield('content')
        </div>

        <footer class="footer py-4  ">
            <div class="container-fluid">
                <div class="row align-items-center justify-content-lg-between">
                    <div class="col-lg-6 mb-lg-0 mb-4">
                        <div class="copyright text-center text-sm text-muted text-lg-start">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>,
                            ProfitRefer
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                            <li class="nav-item">
                                <a href="https://profitrefer.com/privacypolicy.html" class="nav-link text-muted"
                                    target="_blank">Privacy Policy</a>
                            </li>
                            <li class="nav-item">
                                <a href="https://profitrefer.com/termsandconditions.html" class="nav-link text-muted"
                                    target="_blank">Terms & Conditions</a>
                            </li>
                            
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </main>
</body>

</html>

<!--   Core JS Files   -->
<script src="{{asset('adminDashboard/assets/plugins/jquery/js/jquery.min.js') }}"></script>
<script src="{{asset('adminDashboard/assets/plugins/data-tables/js/datatables.min.js') }}"></script>
<script src="{{asset('adminDashboard/assets/plugins/sweetalert/js/sweetalert.min.js')}}"></script>
<script src="{{asset('adminDashboard/assets/js/core/popper.min.js')}}"></script>
<script src="{{asset('adminDashboard/assets/js/core/bootstrap.min.js')}}"></script>
<script src="{{asset('adminDashboard/assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
<script src="{{asset('adminDashboard/assets/js/plugins/smooth-scrollbar.min.js')}}"></script>
<script src="{{asset('adminDashboard/assets/js/plugins/chartjs.min.js')}}"></script>
<script src="{{asset('adminDashboard/assets/js/soft-ui-dashboard.min.js')}}"></script>
<script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
        var options = {
            damping: '0.5'
        }
        Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
</script>
@yield('scripts')
