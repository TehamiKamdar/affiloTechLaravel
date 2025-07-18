@extends("layouts.admin.layout")

@section('styles')
@endsection

@section('scripts')
    <!-- Any additional scripts can be added here -->
@endsection

@section("content")
    <!--begin::Content-->
    <div class="d-flex flex-column flex-column-fluid" id="kt_content">
        <!--begin::Post-->
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <!--begin::Container-->
            <div id="kt_content_container" class="container-fluid">
                <!--begin::Table-->
                <div class="card">
                    <!--begin::Card header-->
                    <div class="card-header">
                        <!--begin::Card title-->
                        <div class="card-title flex-column">
                            <h3 class="fw-bold mb-1">View API Advertiser ({{ $advertiser->name }})</h3>
                        </div>
                        <!--end::Card title-->

                        <!--begin::Card toolbar-->
                        <div class="card-toolbar my-1">
                            <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
                                @php
                                    $routes = [
                                        'admin.advertisers.api.view' => 'Intro',
                                        'admin.advertisers.api.view.commission-rates' => 'Commission Rates',
                                        'admin.advertisers.api.view.terms' => 'Terms'
                                    ];
                                @endphp

                                @foreach($routes as $route => $label)
                                    <li class="nav-item mt-2">
                                        <a class="nav-link text-active-primary ms-0 me-10 py-5
                                            @if(request()->route()->getName() == $route) active @endif"
                                           href="{{ route($route, ['advertiser' => $id]) }}">{{ $label }}</a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <!--end::Card toolbar-->
                    </div>
                    <!--end::Card header-->

                    <!--begin::Card body-->
                    <div class="card-body pt-0">

                        @include("partial.admin.alert")

                        @if(request()->route()->getName() == "admin.advertisers.api.view")
                            @include("admin.advertiser.intro", compact('advertiser', 'primaryRegions', 'methods', 'restrictions', 'countryFullName', 'supportedRegions'))

                        @elseif(request()->route()->getName() == "admin.advertisers.api.view.commission-rates")
                            @include("admin.advertiser.viewCommission", compact('commissions'))

                        @elseif(request()->route()->getName() == "admin.advertisers.api.view.terms")
                            @include("admin.advertiser.terms", compact('advertiser'))

                        @endif

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Container-->
        </div>
        <!--end::Post-->
    </div>
@endsection
