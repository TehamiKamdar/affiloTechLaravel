<div id="kt_app_footer" class="app-footer">
    <!--begin::Footer container-->
    <div class="app-container container-xxl d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
        <!--begin::Copyright-->
        <div class="text-gray-900 order-2 order-md-1">
            <span class="text-muted fw-semibold me-1">{{ now()->format("Y") }}&copy;</span>
            <a href="{{ route("publisher.dashboard") }}" target="_blank" class="text-gray-800 text-hover-primary">Theaffilo</a>
        </div>
        <!--end::Copyright-->
        <!--begin::Menu-->
        <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
            <li class="menu-item">
                <a href="{{ env("ROOT_DOMAIN") }}/terms" target="_blank" class="menu-link px-2">Terms & Conditions</a>
            </li>
            <li class="menu-item">
                <a href="{{ env("ROOT_DOMAIN") }}/privacy" target="_blank" class="menu-link px-2">Privacy Policy</a>
            </li>
        </ul>
        <!--end::Menu-->
    </div>
    <!--end::Footer container-->
</div>
