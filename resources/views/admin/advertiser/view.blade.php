@extends("layouts.admin.layout")

@section("styles")
    <style>
        .nav-pills {
            padding: 0 !important;
        }

        .nav-pills .nav-link {
            padding: 6px 10px;
            font-size: 14px;
            color: #333;
        }

        .nav-pills .nav-link.active,
        .nav-pills .show>.nav-link {
            background: transparent !important;
            color: #f97316 !important;
            font-weight: bold;
            font-size: 14px;
            border-bottom: 2px solid #f97316;
        }
    </style>
@endsection

@section('content')

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                    <div class="col-12">
                        <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="ri-home-5-line text-primary"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route("admin.advertisers.api") }}">Advertisers</a></li>
                        <li class="breadcrumb-item"><a href="">View API Advertiser</a></li>
                        <li class="breadcrumb-item"><a href="">{{ $advertiser->name }}</a></li>
                    </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Base style - Hover table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="float-start">View - API Advertiser ({{ $advertiser->name }})</h5>

                    <ul class="nav nav-pills float-end">
                        @php
                            $routes = [
                                'admin.advertisers.api.view' => 'Intro',
                                'admin.advertisers.api.view.commission-rates' => 'Commission Rates',
                                'admin.advertisers.api.view.terms' => 'Terms'
                            ];
                        @endphp
                        @foreach($routes as $route => $label)
                            <li class="nav-item">
                                <a class="nav-link
                                            @if(request()->route()->getName() == $route) active @endif"
                                   href="{{ route($route, ['advertiser' => $id]) }}">{{ $label }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card-body">
                    @include("partial.admin.alert")

                    @if(request()->route()->getName() == "admin.advertisers.api.view")
                        @include("admin.advertiser.intro", compact('advertiser', 'primaryRegions', 'methods', 'restrictions', 'countryFullName', 'supportedRegions'))

                    @elseif(request()->route()->getName() == "admin.advertisers.api.view.commission-rates")
                        @include("admin.advertiser.viewCommission", compact('commissions'))

                    @elseif(request()->route()->getName() == "admin.advertisers.api.view.terms")
                        @include("admin.advertiser.terms", compact('advertiser'))

                    @endif
                </div>
            </div>
        </div>
        <!-- Base style - Hover table end -->
    </div>

@endsection

@section("scripts")

@endsection
