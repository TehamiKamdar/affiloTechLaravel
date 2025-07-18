{{-- <!--begin::Details-->
<div class="d-flex flex-wrap flex-sm-nowrap">
    <!--begin: Pic-->
    <div class="me-7 mb-4">
        <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
            <img src="@if(isset(auth()->user()->publisher->image)) {{ \App\Helper\Methods::staticAsset("
                storage/".auth()->user()->publisher->image) }} @else {{
            \App\Helper\Methods::staticAsset('assets/media/avatars/blank.png') }} @endif" alt="image" />
        </div>
    </div>
    <!--end::Pic-->
    <!--begin::Info-->
    <div class="flex-grow-1">
        <!--begin::Title-->
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
            <!--begin::User-->
            <div class="d-flex flex-column">
                <!--begin::Name-->
                <div class="d-flex align-items-center mb-2">
                    <div class="text-gray-900 fs-2 fw-bold me-1">{{ auth()->user()->name }}</div>
                </div>
                <!--end::Name-->
                <!--begin::Info-->
                <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                    <div class="d-flex align-items-center text-gray-500 me-5 mb-2">
                        <i class="ki-duotone ki-profile-circle fs-4 me-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>Publisher ID: {{ auth()->user()->uid }}
                    </div>

                </div>
                <!--end::Info-->
            </div>
            <!--end::User-->

        </div>
        <!--end::Title-->
        <!--begin::Stats-->
        <div class="d-flex flex-wrap flex-stack">
            <!--begin::Wrapper-->

            <!--end::Wrapper-->
            <!--begin::Progress-->
            <div class="d-flex align-items-center w-100 w100 flex-column mt-3">
                <div class="d-flex justify-content-between w-100 mt-auto mb-2">
                    <span class="fw-semibold fs-6 text-gray-500">Profile
                        Compleation</span>
                    <span class="fw-bold fs-6">100%</span>

                </div>
                <div class="h-5px mx-3 w-100 bg-light mb-3">
                    <div class="bg-success rounded h-5px" role="progressbar" style="width: 100%;" aria-valuenow="50"
                        aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <span class="fw-semibold fs-6 text-gray-500 text-center">5/5 -
                    Profile Completed</span>
            </div>
            <!--end::Progress-->
        </div>
        <!--end::Stats-->
    </div>
    <!--end::Info-->
</div>
<!--end::Details--> --}}
<!--begin::Navs-->

<div class="page-header min-height-250 border-radius-lg my-4 d-flex flex-column justify-content-end">
    <span class="mask bg-dark opacity-9"></span>
    <div class="w-100 position-relative p-3">
        <div class="d-flex justify-content-between align-items-end">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-xl position-relative me-3">
                    <img src="@if(isset(auth()->user()->publisher->image)) {{ $publisher->image ? asset('storage/' . $publisher->image) : asset('assets/media/avatars/blank.png') }} @else {{ \App\Helper\Methods::staticAsset('assets/media/avatars/blank.png') }} @endif"
                        alt="Publisher Avatar">
                </div>
                <div>
                    <h5 class="mb-1 text-white font-weight-bolder">
                        {{ auth()->user()->name }}
                    </h5>
                    <p class="mb-0 text-white text-sm">
                        Publisher ID: {{ auth()->user()->uid }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<ul class="nav mb-4" id="mainTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="{{ route('publisher.profile.basic-information') }}"
            class="btn btn-outline-primary mb-0 btn-sm @if(request()->route()->getName() == "publisher.profile.basic-information") active @endif">Basic
            Information</a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('publisher.profile.company-information') }}"
            class="btn btn-outline-primary mb-0 mx-1 btn-sm @if(request()->route()->getName() == "publisher.profile.company-information") active @endif">Company</a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('publisher.profile.website') }}"
            class="btn btn-outline-primary mb-0 btn-sm @if(request()->route()->getName() == "publisher.profile.website") active @endif">Websites</a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('publisher.profile.payment-billing') }}"
            class="btn btn-outline-primary mb-0  mx-1 btn-sm @if(request()->route()->getName() == "publisher.profile.payment-billing") active @endif">Billing
            & Payments</a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('publisher.profile.login-information.change-email') }}"
            class="btn btn-outline-primary mb-0 btn-sm @if(request()->route()->getName() == "publisher.profile.login-information.change-email") active @endif">Login
            Info</a>
    </li>
</ul>
