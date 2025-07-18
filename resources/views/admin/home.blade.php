@extends('layouts.admin.layout')

@section('styles')
    <style>
        .equal-width {
            width: 200px;
            /* or whatever fixed width works best */
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .loading {
            animation: blink 1s linear infinite;
        }

        @keyframes blink {
            0% {
                opacity: 1.0
            }

            /* Orange */
            10% {
                opacity: 0.8
            }

            /* Yellow */
            20% {
                opacity: 0.6
            }

            /* Green */
            30% {
                opacity: 0.4
            }

            /* Blue */
            40% {
                opacity: 0.2
            }

            /* Indigo */
            50% {
                opacity: 1.0
            }

            /* Pink */
            60% {
                opacity: 0.2
            }

            /* Red */
            70% {
                opacity: 0.4
            }

            /* Teal */
            80% {
                opacity: 0.6
            }

            /* Violet */
            90% {
                opacity: 0.8
            }

            /* Gold */
            100% {
                opacity: 1.0
            }

            /* Back to Orange */
        }
    </style>
@endsection

@section('content')

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="ri-home-5-line text-primary"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <div class="row">
        <div class="col-lg-4 col-12 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card">
                <span class="mask bg-white opacity-10 "></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-primary-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-money-dollar-circle-fill text-primary ri-2x"></i>
                            </div>
                            <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                ${{ \App\Helper\Methods::numberFormatShort($total_sales) }}
                            </h5>
                            <span class="text-secondary text-sm">Total Sales</span>
                        </div>
                        <div class="col-4">
                            <div class="dropdown text-end mb-6">
                                <a href="javascript:;" class="cursor-pointer" id="dropdownUsers1" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-white"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers1">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                            <p class="text-success text-md text-end font-weight-bolder mt-auto mb-0"><i
                                    class="ri-lg ri-arrow-left-up-box-line"></i>55%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card">
                <span class="mask bg-white opacity-10 "></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-purple-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-money-dollar-circle-fill text-purple ri-2x"></i>
                            </div>
                            <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                ${{ \App\Helper\Methods::numberFormatShort($total_commission) }}
                            </h5>
                            <span class="text-secondary text-sm">Total Commissions</span>
                        </div>
                        <div class="col-4">
                            <div class="dropdown text-end mb-6">
                                <a href="javascript:;" class="cursor-pointer" id="dropdownUsers2" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-white"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers2">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                            <p class="text-success text-md text-end font-weight-bolder mt-auto mb-0"><i
                                    class="ri-lg ri-arrow-left-up-box-line"></i>55%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card">
                <span class="mask bg-white opacity-10 "></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-green-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-money-dollar-circle-fill text-green ri-2x"></i>
                            </div>
                            <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                ${{ \App\Helper\Methods::numberFormatShort($total_approved) }}
                            </h5>
                            <span class="text-secondary text-sm">Approved Commission</span>
                        </div>
                        <div class="col-4">
                            <div class="dropdown text-end mb-6">
                                <a href="javascript:;" class="cursor-pointer" id="dropdownUsers3" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-white"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers3">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                            <p class="text-success text-md text-end font-weight-bolder mt-auto mb-0"><i
                                    class="ri-lg ri-arrow-left-up-box-line"></i>55%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card">
                <span class="mask bg-white opacity-10 "></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-danger-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-money-dollar-circle-fill text-danger ri-2x"></i>
                            </div>
                            <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                ${{ \App\Helper\Methods::numberFormatShort($total_declined) }}
                            </h5>
                            <span class="text-secondary text-sm">Declined Commission</span>
                        </div>
                        <div class="col-4">
                            <div class="dropdown text-end mb-6">
                                <a href="javascript:;" class="cursor-pointer" id="dropdownUsers4" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-white"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers4">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                            <p class="text-success text-md text-end font-weight-bolder mt-auto mb-0"><i
                                    class="ri-lg ri-arrow-left-up-box-line"></i>55%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card">
                <span class="mask bg-white opacity-10 "></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-yellow-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-money-dollar-circle-fill text-warning ri-2x"></i>
                            </div>
                            <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                ${{ \App\Helper\Methods::numberFormatShort($total_pending) }}
                            </h5>
                            <span class="text-secondary text-sm">Pending Commission</span>
                        </div>
                        <div class="col-4">
                            <div class="dropdown text-end mb-6">
                                <a href="javascript:;" class="cursor-pointer" id="dropdownUsers1" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-white"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers1">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                            <p class="text-success text-md text-end font-weight-bolder mt-auto mb-0"><i
                                    class="ri-lg ri-arrow-left-up-box-line"></i>55%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-12 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card">
                <span class="mask bg-white opacity-10 "></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-info-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-money-dollar-circle-fill text-info ri-2x"></i>
                            </div>
                            <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                {{ \App\Helper\Methods::numberFormatShort($latest_invoices) }}

                            </h5>
                            <span class="text-secondary text-sm">Latest Invoices</span>
                        </div>
                        <div class="col-4">
                            <div class="dropdown text-end mb-6">
                                <a href="javascript:;" class="cursor-pointer" id="dropdownUsers2" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-white"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers2">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                            <p class="text-success text-md text-end font-weight-bolder mt-auto mb-0"><i
                                    class="ri-lg ri-arrow-left-up-box-line"></i>55%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Sales Graph Start --}}
    <div class="row my-4">
        <div class="col-12">
            <div class="card z-index-2">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center flex-wrap">
                        <div>
                            <h6 class="text-sm text-center">Sales Graph <span class="text-xs">(Current Month)</span></h6>
                        </div>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="chart position-relative" style="height:250px; overflow-x: auto; overflow-y: hidden;">
                        <div id="chart-bar" style="height: 250px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Sales Graph End --}}


    <div class="row g-4">
        <div class="col-12">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 text-center">Deep Links (Monthly)</h6>
                        </div>
                        <div class="card-body">
                            <div id="deeplinkDonutChart">
                                <p class="loading text-center" id="deeplinkDonutChartLoading">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 text-center">Email Records (Monthly)</h6>
                        </div>
                        <div class="card-body">
                            <div id="emailDonutChart">
                                <p class="loading text-center" id="emailDonutChartLoading">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="mb-0 text-center">Tracking Links (Monthly)</h6>
                        </div>
                        <div class="card-body">
                            <div id="chartTrackingLink">
                                <p class="loading text-center" id="chartTrackingLinkLoading">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="card mb-4">
        <div class="card-header pb-3">
            <h6>Advertisers Overview</h6>
        </div>
    </div>


    <div class="row mt-2">
        <div class="col-12 col-lg-3 col-md-6 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card">
                <span class="mask bg-white opacity-10 "></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-primary-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-user-line text-primary ri-xl"></i>
                            </div>
                            <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                {{ $totalAdvertiser }}
                            </h5>
                            <span class="text-secondary text-sm">Total Advertisers</span>
                        </div>
                        <div class="col-4">
                            <div class="dropdown text-end mb-6">
                                <a href="javascript:;" class="cursor-pointer" id="dropdownUsers1" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-white"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers1">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                            <p class="text-success text-sm text-end font-weight-bolder mt-auto mb-0"><i
                                    class="ri-1x ri-arrow-left-up-box-line"></i>55%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 col-md-6 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card">
                <span class="mask bg-white opacity-10 "></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-purple-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-user-line text-purple ri-xl"></i>
                            </div>
                            <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                {{ $totalnewAdvertiser }}
                            </h5>
                            <span class="text-secondary text-sm">New Advertisers</span>
                        </div>
                        <div class="col-4">
                            <div class="dropdown text-end mb-6">
                                <a href="javascript:;" class="cursor-pointer" id="dropdownUsers2" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-white"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers2">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                            <p class="text-success text-sm text-end font-weight-bolder mt-auto mb-0"><i
                                    class="ri-1x ri-arrow-left-up-box-line"></i>55%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 col-md-6 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card">
                <span class="mask bg-white opacity-10 "></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-green-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-user-line text-green ri-xl"></i>
                            </div>
                            <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                {{ $totalActive }}
                            </h5>
                            <span class="text-secondary text-sm">Active Advertisers</span>
                        </div>
                        <div class="col-4">
                            <div class="dropdown text-end mb-6">
                                <a href="javascript:;" class="cursor-pointer" id="dropdownUsers3" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-white"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers3">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                            <p class="text-success text-sm text-end font-weight-bolder mt-auto mb-0"><i
                                    class="ri-1x ri-arrow-left-up-box-line"></i>55%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3 col-md-6 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card">
                <span class="mask bg-white opacity-10 "></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-danger-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-user-line text-danger ri-xl"></i>
                            </div>
                            <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                {{ $totalInActive }}
                            </h5>
                            <span class="text-secondary text-sm">Inactive Advertisers</span>
                        </div>
                        <div class="col-4">
                            <div class="dropdown text-end mb-6">
                                <a href="javascript:;" class="cursor-pointer" id="dropdownUsers4" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-h text-white"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3" aria-labelledby="dropdownUsers4">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                            <p class="text-success text-sm text-end font-weight-bolder mt-auto mb-0"><i
                                    class="ri-1x ri-arrow-left-up-box-line"></i>55%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end row -->
    <!-- Advertisers Table -->

    <div class="row my-4">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6>Top 5 Advertisers of this month</h6>
                            <p class="text-sm mb-0">
                            </p>
                        </div>
                        <div class="col-lg-6 col-5 my-auto text-end">
                            <div class="dropdown float-lg-end pe-4">
                                <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-secondary"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7">
                                        Advertiser Name
                                    </th>
                                    <th
                                        class="text-uppercase text-center text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Sales</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Commission
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Transaction</th>

                            </thead>
                            <tbody>

                                @foreach ($topAdvertisers as $tp)
                                    <tr>
                                        @php
                                            $advertiser = App\Models\Advertiser::where(
                                                'advertiser_id',
                                                $tp->advertiser_id,
                                            )->first();
                                          @endphp
                                        <td class="equal-width align-middle text-center text-sm">
                                            <a href="{{ !empty($advertiser->url) ? $advertiser->url : '#' }}" target="_blank"
                                                class="fs-4 fw-bold mb-0 text-center text-sm nav-link">{{ !empty($advertiser->name) ? $advertiser->name : '#' }}</a>
                                        </td>
                                        <td class="equal-width align-middle text-center text-sm">
                                            <span class="text-sm fw-bold">${{ number_format($tp->total_sales, 2) }}</span>
                                        </td>
                                        <td class="equal-width align-middle text-center text-sm">
                                            <span> ${{ number_format($tp->total_commission, 2) }} </span>
                                        </td>
                                        <td class="equal-width align-middle text-center text-sm">
                                            <span>{{ $tp->transaction_count }}</span=>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Publishers Table -->

    <div class="row my-4">
        <div class="col-lg-12 col-md-6 mb-md-0 mb-4">
            <div class="card">
                <div class="card-header pb-0">
                    <div class="row">
                        <div class="col-lg-6 col-7">
                            <h6>Top 5 Publishers of this month</h6>
                            <p class="text-sm mb-0">
                            </p>
                        </div>
                        <div class="col-lg-6 col-5 my-auto text-end">
                            <div class="dropdown float-lg-end pe-4">
                                <a class="cursor-pointer" id="dropdownTable" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-ellipsis-v text-secondary"></i>
                                </a>
                                <ul class="dropdown-menu px-2 py-3 ms-sm-n4 ms-n5" aria-labelledby="dropdownTable">
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Action</a></li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Another action</a>
                                    </li>
                                    <li><a class="dropdown-item border-radius-md" href="javascript:;">Something else
                                            here</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Domain</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Sales</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Commission
                                    </th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Transaction</th>

                            </thead>
                            <tbody>

                                @foreach ($topPublishers as $tp)
                                    <tr>
                                        @php
                                            $user = App\Models\User::where('publisher_id', $tp->publisher_id)->first();
                                            if ($user) {
                                                $website = App\Models\Website::where(
                                                    'id',
                                                    $user->active_website_id,
                                                )->first();
                                            }
                                          @endphp
                                        <td class="equal-width align-middle text-center text-sm">
                                            <a href="{{ !empty($website->url) ? $website->url : '#' }}" target="_blank"
                                                class="fs-4 fw-bold mb-0 text-center text-sm nav-link">{{ !empty($website->name) ? $website->name : '#' }}</a>
                                        </td>
                                        <td class="equal-width align-middle text-center text-sm">
                                            <span class="text-sm fw-bold">${{ number_format($tp->total_sales, 2) }}</span>
                                        </td>
                                        <td class="equal-width align-middle text-center text-sm">
                                            <span>${{ number_format($tp->total_commission, 2) }}</span>
                                        </td>
                                        <td class="equal-width align-middle text-center text-sm">
                                            <span>{{ $tp->transaction_count }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header bg-white text-white">
            <h6>Advertisers Information</h6>
        </div>
        <div class="card-body p-3">
            <!-- Desktop Table (shown on md and larger screens) -->
            <div class="table-responsive d-none d-md-block">
                <table class="table table-hover">
                    <tbody>
                        @foreach ($advertisers as $advertiser)
                            <tr class="align-middle border-bottom">
                                <!-- Desktop table content remains the same -->
                                <td class="equal-width">
                                    <div>
                                        <small class="text-muted d-block text-xs fw-bold">Advertiser Name</small>
                                        <h6 class="fw-semibold text-dark mt-2">{{ !empty($advertiser->name) ? $advertiser->name : '#' }}.</h6>
                                        <a href="{{ !empty($advertiser->url) ? $advertiser->url : '#' }}" target="_blank"
                                            class="fw-semibold nav-link text-xs mt-2"><i class="ri-link-m"></i>Visit
                                            Website</a>
                                    </div>
                                </td>
                                <td class="equal-width">
                                    <div>
                                        <small class="text-muted d-block text-xs fw-bold">Joining Date</small>
                                        <h6 class="fw-semibold text-dark mt-2">
                                            {{ \Carbon\Carbon::parse($advertiser->created_at)->format('m/d/Y') }}
                                        </h6>
                                    </div>
                                </td>
                                <td class="equal-width">
                                    @foreach ($advertiser->primary_regions as $pr)
                                        <div>
                                            <small class="text-muted d-block text-xs fw-bold">Region</small>
                                            <img src="https://flagsapi.com/{{$pr}}/flat/32.png" title="{{$pr}}"
                                                class="img-fluid cursor-pointer" alt="{{$pr}} Flag">
                                        </div>
                                    @endforeach
                                </td>
                                <td class="equal-width">
                                    <div>
                                        <small class="text-muted d-block text-xs fw-bold">Status</small>
                                        @if ($advertiser->status == 1)
                                            <div class="d-flex align-items-center">
                                                <span class="rounded-circle bg-success me-2"
                                                    style="width:10px; height:10px;"></span>
                                                <h6 class="fw-semibold text-dark mt-2">Active</h6>
                                            </div>
                                        @elseif($advertiser->status == 0)
                                            <div class="d-flex align-items-center">
                                                <span class="rounded-circle bg-warning me-2"
                                                    style="width:10px; height:10px;"></span>
                                                <h6 class="fw-semibold text-dark mt-2">Pending</h6>
                                            </div>
                                        @else
                                            <div class="d-flex align-items-center">
                                                <span class="rounded-circle bg-danger me-2" style="width:10px; height:10px;"></span>
                                                <h6 class="fw-semibold text-dark mt-2">Approval Required</h6>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Mobile Grid (shown on sm and xs screens) -->
            <div class="d-md-none">
                @foreach ($advertisers as $advertiser)
                    <div class="card-body border-bottom">
                        <div class="row g-2">
                            <!-- Row 1 -->
                            <div class="col-8">
                                <small class="text-muted text-xs fw-bold">Advertiser</small>
                                <h6 class="fw-semibold text-dark mb-0 text-truncate">{{ !empty($advertiser->name) ? $advertiser->name : '#' }}</h6>
                                <a href="{{ !empty($advertiser->url) ? $advertiser->url : '#' }}" target="_blank" class="fw-semibold nav-link text-xs mt-1"><i
                                        class="ri-link-m"></i>Visit Website</a>
                            </div>
                            <div class="col-4">
                                <small class="text-muted text-xs fw-bold">Status</small>
                                @if ($advertiser->status == 1)
                                    <div class="d-flex align-items-center">
                                        <span class="rounded-circle bg-success me-1" style="width:8px; height:8px;"></span>
                                        <span class="fw-semibold text-dark text-xs">Active</span>
                                    </div>
                                @elseif($advertiser->status == 0)
                                    <div class="d-flex align-items-center">
                                        <span class="rounded-circle bg-warning me-1" style="width:8px; height:8px;"></span>
                                        <span class="fw-semibold text-dark text-xs">Pending</span>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center">
                                        <span class="rounded-circle bg-danger me-1" style="width:8px; height:8px;"></span>
                                        <span class="fw-semibold text-dark text-xs">Approval Req.</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Row 2 -->
                            <div class="col-6">
                                <small class="text-muted text-xs fw-bold">Joined</small>
                                <h6 class="fw-semibold text-dark mb-0 text-xs">
                                    {{ \Carbon\Carbon::parse($advertiser->created_at)->format('m/d/Y') }}
                                </h6>
                            </div>
                            <div class="col-6 d-flex justify-content-center align-items-center">
                                <small class="text-muted text-xs fw-bold">Region: </small>
                                <div>
                                    @foreach ($advertiser->primary_regions as $pr)
                                        <img src="https://flagsapi.com/{{$pr}}/flat/24.png" title="{{$pr}}"
                                            class="img-fluid cursor-pointer" alt="{{$pr}} Flag">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ \App\Helper\Methods::staticAsset('dashboard_assets/js/core/popper.min.js') }}"></script>
    <script src="{{ \App\Helper\Methods::staticAsset('dashboard_assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ \App\Helper\Methods::staticAsset('dashboard_assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ \App\Helper\Methods::staticAsset('dashboard_assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ \App\Helper\Methods::staticAsset('dashboard_assets/js/plugins/chartjs.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.5.0/apexcharts.min.js"
        integrity="sha512-yMnvLee1a5S9nemgCoMth5YvOchnQMFMOSao/bH6SLAXZnauuHs1gd92DnE9+sVQ5aglei3LZDelg8LauSlWkw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function () {
            fetch("{{ route('admin.chart.data') }}")
                .then(response => response.json())
                .then(data => {
                    console.log(data)
                    const { labels, barData } = data;

                    const options = {
                        chart: {
                            type: 'bar',
                            height: 250,
                            toolbar: { show: false }
                        },
                        colors: ['#f97316'],
                        series: [{
                            name: 'Sales',
                            data: barData
                        }],
                        xaxis: {
                            categories: labels,
                            labels: { rotate: -45 }
                        },
                        yaxis: {
                            labels: {
                                formatter: function (val) {
                                    return Number(val).toLocaleString(undefined, { maximumFractionDigits: 0 });
                                }
                            }
                        },
                        tooltip: {
                            y: {
                                formatter: function (val) {
                                    return Number(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }
                            }
                        },
                        dataLabels: { enabled: false },
                        legend: {
                            show: true,
                            position: 'bottom'
                        },
                        plotOptions: {
                            bar: {
                                columnWidth: '60%',
                                borderRadius: 8
                            }
                        }
                    };

                    new ApexCharts(document.querySelector("#chart-bar"), options).render();
                })
                .catch(error => console.error("Chart Load Error:", error));
        });
    </script>
    <script type="text/javascript">
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>


    <script type="text/javascript">
        async function renderTrackingLinkDonutChart() {
            const chartContainer = document.querySelector("#chartTrackingLink");
            const loadingText = document.querySelector("#chartTrackingLinkLoading");
            try {
                if (loadingText) loadingText.textContent = "Loading...";
                const response = await fetch('/admin/tracking-link-chart');
                const data = await response.json();

                if (loadingText) loadingText.remove();
                console.log(data); // ✅ Confirm response contains series and labels

                const options = {
                    chart: {
                        type: 'donut',
                        height: 260
                    },
                    series: data.series,
                    labels: data.labels,
                    colors: ['#22c55e', '#FFBF00', '#FF7F50'],
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        }
                                    }
                                }
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                };

                new ApexCharts(document.querySelector("#chartTrackingLink"), options).render();

            } catch (error) {
                console.error("Error loading donut chart:", error);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            renderTrackingLinkDonutChart();
        });



        async function renderDeeplinkDonutChart() {

            const chartContainer = document.querySelector("#deeplinkDonutChart");
            const loadingText = document.querySelector("#deeplinkDonutChartLoading");
            try {
                if (loadingText) loadingText.textContent = "Loading...";
                const response = await fetch('/admin/deeplink-chart');
                const data = await response.json();
                if (loadingText) loadingText.remove();
                const options = {
                    chart: {
                        type: 'donut',
                        height: 260
                    },
                    series: data.series,
                    labels: data.labels,
                    colors: ['#22c55e', '#FFBF00', '#FF7F50'],
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        }
                                    }
                                }
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                };

                new ApexCharts(document.querySelector("#deeplinkDonutChart"), options).render();
            } catch (error) {
                console.error("Failed to load chart:", error);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            renderDeeplinkDonutChart();
        });

        chartTrackingLink
        chartTrackingLinkLoading
        async function renderEmailDonutChart() {
            const chartContainer = document.querySelector("#emailDonutChart");
            const loadingText = document.querySelector("#emailDonutChartLoading");

            try {
                // Show loading
                if (loadingText) loadingText.textContent = "Loading...";

                const response = await fetch('/admin/email-chart');
                const data = await response.json();

                // Remove loading text
                if (loadingText) loadingText.remove();

                const options = {
                    chart: {
                        type: 'donut',
                        height: 260
                    },
                    series: data.series,
                    labels: data.labels,
                    colors: ['#22c55e', '#FFBF00', '#FF7F50'],
                    plotOptions: {
                        pie: {
                            donut: {
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        label: 'Total',
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                                        }
                                    }
                                }
                            }
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                };

                new ApexCharts(chartContainer, options).render();
            } catch (error) {
                if (loadingText) loadingText.textContent = "Failed to load chart.";
                console.error("Failed to load chart:", error);
            }
        }

        document.addEventListener("DOMContentLoaded", function () {
            renderEmailDonutChart();
        });
    </script>
@endsection
