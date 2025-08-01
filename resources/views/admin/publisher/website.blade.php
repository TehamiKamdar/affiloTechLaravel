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
            color: #007bff !important;
            font-weight: bold;
            font-size: 14px;
            border-bottom: 2px solid #007bff;
        }
    </style>
@endsection
@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Base style - Hover table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header justify-content-between">
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
<!-- [ accordion-collapse ] start -->
<div class="col-sm-12">
    <div class="accordion" id="accordionExample">

        @foreach($websites as $key => $website)

            <div class="card">
                <div class="card-header" id="heading{{ $key }}">
                    <h5 class="mb-0"><a href="#!" data-bs-toggle="collapse"
                                        data-bs-target="#collapse{{ $key }}" aria-expanded="true"
                                        aria-controls="collapse{{ $key }}">{{ $website->name }}</a></h5>
                </div>
                <div id="collapse{{ $key }}" class="card-body collapse @if($key == 0) show @endif"
                     aria-labelledby="heading{{ $key }}" data-bs-parent="#accordionExample">
                    <div class="table-responsive">
                        <table class="table invoice-detail-table">
                            <thead>
                                <tr class="thead-default">
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7" style="width: 30%">Key</th>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Value</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>URL</th>
                                    <td>
                                        {{ $website->url }}
                                        @if($website->status == \App\Models\User::STATUS_PENDING)
                                            <span class="pcoded-badge label label-warning">Pending</span>
                                        @elseif($website->status == \App\Models\User::STATUS_HOLD)
                                            <span class="pcoded-badge label label-info">Hold</span>
                                        @elseif($website->status == \App\Models\User::STATUS_REJECT)
                                            <span class="pcoded-badge label label-danger">Rejected</span>
                                        @else
                                            <span class="pcoded-badge label label-success">Verified</span>
                                        @endif

                                        @if($website->status != \App\Models\User::STATUS_REJECT)
                                            <a href="{{ route('admin.publishers.website.statusUpdate', ['website' => $website->id, 'status' => \App\Models\User::STATUS_REJECT]) }}" class="btn btn-sm btn-glow-danger btn-danger float-end">Reject</a>
                                        @endif
                                        @if($website->status != \App\Models\User::STATUS_HOLD)
                                            <a href="{{ route('admin.publishers.website.statusUpdate', ['website' => $website->id, 'status' => \App\Models\User::STATUS_HOLD]) }}" class="btn btn-sm btn-glow-info btn-info float-end mr-2">Hold</a>
                                        @endif
                                        @if($website->status != \App\Models\User::STATUS_ACTIVE)
                                            <a href="{{ route('admin.publishers.website.statusUpdate', ['website' => $website->id, 'status' => \App\Models\User::STATUS_ACTIVE]) }}" class="btn btn-sm btn-glow-success btn-success float-end mr-2">Active</a>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Website ID</th>
                                    <td>{{ $website->wid }}</td>
                                </tr>
                                <tr>
                                    <th>Country</th>
                                    <td>{{ $website->getCountry->name }}</td>
                                </tr>
                                <tr>
                                    <th>Monthly Traffic</th>
                                    <td>{{ $website->monthly_traffic ?? "-" }}</td>
                                </tr>
                                <tr>
                                    <th>Monthly Page Views</th>
                                    <td>{{ $website->monthly_page_views ?? "-" }}</td>
                                </tr>
                                <tr>
                                    <th>Intro</th>
                                    <td>{{ $website->intro ?? "-" }}</td>
                                </tr>
                                <tr>
                                    <th>Created At</th>
                                    <td>{{ $website->created_at ?? "-" }}</td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td>{{ $website->updated_at ?? "-" }}</td>
                                </tr>
                            </tbody>
                        </table>
                        @if($website->status != \App\Models\User::STATUS_ACTIVE)

                            @php
                                $class = "alert-warning";
                                $message = "After the publisher verifies the website successfully, the website status should also be updated accordingly.";
                                if($website->status == \App\Models\User::STATUS_REJECT)
                                {
                                    $class = "alert-danger";
                                    $message = "The admin's has put the website on reject.";
                                }
                                elseif($website->status == \App\Models\User::STATUS_HOLD)
                                {
                                    $class = "alert-info";
                                    $message = "The admin's has put the website on hold.";
                                }
                            @endphp

                            <!--begin::Notice-->
                            <div class="alert {{ $class }}" role="alert">
                                <h4 class="alert-heading">Website status still in {{ $website->status }}!</h4>
                                <p>{{ $message }}</p>
                            </div>
                            <!--end::Notice-->
                        @endif
                    </div>
                </div>
            </div>

        @endforeach

    </div>
</div>
<!-- [ accordion-collapse ] end -->

@endsection
