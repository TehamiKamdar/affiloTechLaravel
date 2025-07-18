@extends('layouts.admin.layout')
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
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Base style - Hover table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="float-start">View - Publisher ( {{ $publisher->name }} )</h5>
                    <ul class="nav nav-pills float-end">
                        @php
                            $routes = [
                                'admin.publishers.view' => 'Intro',
                                'admin.publishers.view.mediakits' => 'Media Kits',
                                'admin.publishers.view.websites' => 'Websites',
                                'admin.publishers.view.billing-info' => 'Billing',
                                'admin.publishers.view.payment-info' => 'Payment',
                                'admin.publishers.view.lock-unlock.network-by-advertiser' => 'Lock Unlock Advertiser'
                            ];
                        @endphp
                        @foreach($routes as $route => $label)
                            <li class="nav-item">
                                <a class="nav-link @if(request()->route()->getName() === $route) active @endif"
                                    href="{{ route($route, ['publisher' => $id]) }}">
                                    {{ $label }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                </div>
                {{-- <div class="card-body">
                    @include("partial.admin.alert")
                    @if(request()->route()->getName() == "admin.publishers.view")
                        @include("admin.publisher.intro", compact('publisher', 'company'))
                    @elseif(request()->route()->getName() == "admin.publishers.view.mediakits")
                        @include("admin.publisher.media-kits", compact('publisher'))
                    @elseif(request()->route()->getName() == "admin.publishers.view.websites")
                        @include("admin.publisher.website", compact('publisher', 'websites'))
                    @elseif(request()->route()->getName() == "admin.publishers.view.billing-info")
                        @include("admin.publisher.billing-information", compact('publisher'))
                    @elseif(request()->route()->getName() == "admin.publishers.view.payment-info")
                        @include("admin.publisher.payment-information", compact('publisher'))
                    @elseif(request()->route()->getName() == "admin.publishers.view.lock-unlock.network-by-advertiser")
                        @include("admin.publisher.lock-unlock-network.advertiser", compact('publisher', 'advertisers', 'from', 'to', 'perPage'))
                    @endif
                </div> --}}
            </div>
        </div>
        <!-- Base style - Hover table end -->
    </div>
    @php $title = "No Media Kits Available"; @endphp
    @include("partial.no", compact('title'))
    <!-- [ Main Content ] start -->

@endsection


