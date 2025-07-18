@extends("layouts.admin.layout")

@pushonce('styles')

    <style>
        .table-social tbody tr td:not(:first-child) {
            text-align: left !important;
        }
        .card-header {
            padding: 0.75rem 1rem !important;
        }
        .card .card-header {
            text-transform: none !important;
            min-height: 40px !important;
        }
        .changelog__according .card .card-header {
            min-height: 40px !important;
            height: 40px !important;
        }
        .changelog__accordingCollapsed {
            height: 40px !important;
        }
        .v-num {
            font-size: 14px !important;
        }
        .btn-xs {
            line-height: 1.7 !important;
            font-size: 10px !important;
        }
        .table, .changelog__according .card:not(:last-child) {
            margin-bottom: 0 !important;
        }
        .social-dash-wrap .card.card-overview {
            margin-bottom: 5%;
        }
        .social-dash-wrap .card-body {
            padding: 0 !important;
        }
        .changelog__according {
            margin-top: 0 !important;
        }
        .width-25 {
            width: 25%;
        }
        .min-height-zero {
            min-height: 0
        }

        .table>tbody>tr>td {
            width: 50% !important;
        }
    </style>

@endpushonce

@section('content')

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="feather icon-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Coupon Detail</a></li>
                        <li class="breadcrumb-item"><a href="{{ route("admin.creatives.index") }}">Coupons</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
            <div class="social-dash-wrap">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-overview border-0">
                            <div class="card-header">
                                <h6></h6>
                                <div class="card-extra">
                                    <div class="card-tab btn-group nav nav-tabs">
                                        <a class="btn btn-xs btn-white active border-light" id="overview_tab" data-toggle="tab" href="#overview" role="tab" area-controls="intro" aria-selected="true">Overview</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-0">

                                @include("partial.admin.alert")

                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="overview" role="" aria-labelledby="overview_tab">
                                        <div class="table-responsive">
                                            <span style="color: black;font-size: 24px;font-weight: 900;">{{ \Illuminate\Support\Str::title($user->type) }}</span>
                                            <table class="table table-bordered table-social">

                                                <thead>
                                                    <tr>
                                                        <th scope="col"  class="width-25">Field</th>
                                                        <th scope="col">Value</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th>
                                                           ID
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->id }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                           Advertiser ID
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->advertiser_id }}
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <th>
                                                         Internal  Advertiser ID
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->internal_advertiser_id }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                           Advertiser Name
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->advertiser_name }}
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <th>
                                                            Title
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->title }}
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <th>
                                                            Description
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->description }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Type
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->type ?? "-" }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <th>
                                                            Source
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->source ?? "-" }}
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <th>
                                                           Code
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->code ?? "-" }}
                                                        </td>
                                                    </tr>
                                                     <tr>
                                                        <th>
                                                            Promotion Id
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->promotion_id ?? "-" }}
                                                        </td>
                                                    </tr>

                                                                                                         <tr>
                                                        <th>
                                                            Url tracking
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->url_tracking ?? "-" }}
                                                        </td>
                                                    </tr>

                                                    </tr>

                                                                                                         <tr>
                                                        <th>
                                                            Url
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ $user->url ?? "-" }}
                                                        </td>
                                                    </tr>

                                                    </tr>

                                                                                                         <tr>
                                                        <th>
                                                            Start Date - End Date
                                                        </th>
                                                        <td style="width:50%">
                                                            {{ date($user->start_date) ?? "-" }} -  {{ date($user->end_date) ?? "-" }}
                                                        </td>
                                                    </tr>



                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

@section("scripts")
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ \App\Helper\Methods::staticAsset('panel/assets/plugins/data-tables/js/datatables.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {

            $('#transactionListing').dataTable({
                scrollY: true,          // Enable vertical scrolling
                scrollX: true,          // Enable horizontal scrolling
                scrollCollapse: true,   // Allow scrolling collapse when content is smaller
                paging: true,           // Enable pagination
                autoWidth: false,       // Prevent automatic column width adjustment
                responsive: false,      // Disable responsive behavior if not needed
                ordering: true,         // Enable column ordering
                pageLength: 250,
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                ajax: {
                    url: "{{ route('admin.creatives.creativeajax') }}",
                    data: function (d) {
                        {{--d.source = $('#source').val();--}}
                        {{--d.country = $('#country').val();--}}
                        {{--d.search_filter = $('#search_filter').val();--}}
                        {{--d.payment_id = "{{ request()->input('payment_id') ?? '' }}";--}}
                        {{--d.r_name = "{{ request()->input('r_name') ?? '' }}";--}}
                    }
                },
                columns: [
                    {data: 'id', name: 'id'},
                      {data: 'advertiser_id', name: 'advertiser_id'},
                        {data: 'internal_advertiser_id', name: 'internal_advertiser_id'},
                    {data: 'advertiser_name', name: 'advertiser_name'},
                    {data: 'title', name: 'title'},
                    {data: 'description', name: 'description'},
                    {data: 'type', name: 'type'},
                     {data: 'source', name: 'source'},
                      {data: 'code', name: 'code'},
                       {data: 'promtion_id', name: 'promtion_id'},
                        {data: 'url_tracking', name: 'url_tracking'},
                         {data: 'url', name: 'url'},
                          {data: 'start_date', name: 'start_date'},
                           {data: 'end_date', name: 'end_date'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });

        }, false);
    </script>

@endsection
