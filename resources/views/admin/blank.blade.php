@extends("layouts.admin.layout")

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
@endsection

@section('scripts')
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
                            <h3 class="fw-bold mb-1">{{ $title }}</h3>
                        </div>
                        <!--begin::Card title-->
                        <!--begin::Card toolbar-->
                        <div class="card-toolbar my-1">
                            <!--begin::Select-->
                            {{--                            <div class="me-6 my-1">--}}
                            {{--                                <select id="kt_filter_year" name="year" data-control="select2" data-hide-search="true" class="w-125px form-select form-select-solid form-select-sm">--}}
                            {{--                                    <option value="All" selected="selected">All time</option>--}}
                            {{--                                    <option value="thisyear">This year</option>--}}
                            {{--                                    <option value="thismonth">This month</option>--}}
                            {{--                                    <option value="lastmonth">Last month</option>--}}
                            {{--                                    <option value="last90days">Last 90 days</option>--}}
                            {{--                                </select>--}}
                            {{--                            </div>--}}
                            <!--end::Select-->
                            <!--begin::Select-->
                            {{--                            <div class="me-4 my-1">--}}
                            {{--                                <select id="kt_filter_orders" name="orders" data-control="select2" data-hide-search="true" class="w-125px form-select form-select-solid form-select-sm">--}}
                            {{--                                    <option value="All" selected="selected">All Orders</option>--}}
                            {{--                                    <option value="Approved">Approved</option>--}}
                            {{--                                    <option value="Declined">Declined</option>--}}
                            {{--                                    <option value="In Progress">In Progress</option>--}}
                            {{--                                    <option value="In Transit">In Transit</option>--}}
                            {{--                                </select>--}}
                            {{--                            </div>--}}
                            <!--end::Select-->
                        </div>
                        <!--begin::Card toolbar-->
                    </div>
                    <!--end::Card header-->
                    <!--begin::Card body-->
                    <div class="card-body pt-0">

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
