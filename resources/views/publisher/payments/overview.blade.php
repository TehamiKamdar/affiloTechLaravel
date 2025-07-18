@extends('layouts.publisher.layout')

@section('styles')

    <style>
        .table-loader {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            /* Ensure loader is above other content */
        }

        .table-loader .spinner-border {
            width: 3rem;
            height: 3rem;
            border-width: .3em;
        }

        .ml-3 {
            margin-left: 10px;
        }

        .display-hidden {
            display: none;
        }
    </style>

@endsection

@section('scripts')
   <!-- 1. Load required plugins/utilities first -->
<script src="{{ \App\Helper\Methods::staticAsset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ \App\Helper\Methods::staticAsset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>

<!-- 2. Then load custom scripts that depend on KTUtil or others -->
<script src="{{ \App\Helper\Methods::staticAsset("admin/assets/js/custom/apps/ecommerce/customers/details/add-auth-app.js") }}"></script>
<script src="{{ \App\Helper\Methods::staticAsset("src/js/custom/publisher/export.js") }}"></script>

    <script>

        $(document).ready(function () {

            const exportFieldSet = () => {
                $("#search_export").val($('#search').val());
                $("#status_export").val($("#status").val());
                $("#region_export").val($("#region").val());
                $("#advertiser_export").val($("#advertiser").val());
                $("#date_export").val($('#pr_report_datepicker').val());
            }

            const passURLFields = () => {
                updateAndFetch({
                    per_page: $('#per-page-select').val(),
                    page: 1,
                    search: $('#search').val(),
                    date: $('#pr_report_datepicker').val(),
                  
                    sub_id: $('#sub_id').is(":checked")
                });
                exportFieldSet();
            }

            const updateUrlParams = (params = {}) => {
                let url = new URL(window.location.href);
                Object.keys(params).forEach(key => url.searchParams.set(key, params[key]));
                history.pushState(null, '', url.toString());
            };

            const fetchTransactions = (page = 1) => {

                $('#table-loader').show();

                const data = {
                    page,
                    per_page: $('#per-page-select').val(),
                    search: $('#search').val(),
                    date: $('#pr_report_datepicker').val(),
                   
                    sub_id: $('#sub_id').is(":checked")
                };

                $.ajax({
                    url: '{{ route("publisher.finance-overview") }}',
                    data: data,
                    success: function (response) {
                        console.log(`Showing ${response.from} to ${response.to} of ${response.total} entries`)
                        console.log(response.pagination)
                        $('#transaction-container').html(response.data);
                        $('#pagination-container').html(response.pagination);
                        $('#kt_project_users_table_info').html(`Showing ${response.from} to ${response.to} of ${response.total} entries`);
                        // $('#advertiserTotal').val(response.total);
                    },
                    error: function (xhr) {
                        alert('Error occurred: ' + xhr.statusText);
                    },
                    complete: function () {
                        $('#table-loader').hide();
                    }
                });
            };

            const updateAndFetch = (params = {}) => {
                updateUrlParams(params);
                fetchTransactions(params.hasOwnProperty('page') ? params.page : 1);
            };

            let start = moment().startOf("year");
            let end = moment().endOf("year");

            // Function to parse custom date range from URL
            function setDateRangeFromCustomURL(dateRange) {
                let dates = dateRange.split(" - "); // Split the passed date range
                if (dates.length === 2) {
                    start = moment(dates[0], "MMM D, YYYY"); // Parse the start date
                    end = moment(dates[1], "MMM D, YYYY");   // Parse the end date
                }
            }

            // Check if a custom date range is passed in the URL
            @if(request()->date)
                let dateRange = "{{ request()->date }}"; // Extract the date range from the URL
                setDateRangeFromCustomURL(dateRange);    // Set the start and end dates based on the custom date range
            @endif

            // Initialize the date range picker
            $('input[name="date"]').daterangepicker({
                startDate: start,
                endDate: end,
                singleDatePicker: false,
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear'
                },
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                    'Current Year': [moment().startOf('year'), moment().endOf('year')],
                    'Previous Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
                },
            });

            // Default date display in input
            let date = start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY');
            $('input[name="date"]').val(date);

            // Handle apply event for the date picker
            $('input[name="date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MMM D, YYYY') + ' - ' + picker.endDate.format('MMM D, YYYY'));
                passURLFields();
            });

            // Handle cancel event to clear the date input
            $('input[name="date"]').on('cancel.daterangepicker', function (ev, picker) {
                $(this).val('');
                passURLFields();
            });

            // Event Bindings
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                $('.pagination li').removeClass('active');
                $(this).parent('li').addClass('active');
                updateAndFetch({ page: new URL($(this).attr('href')).searchParams.get('page') });
            });

            $('#per-page-select, #statusFilter, #apply').on('change click', function () {
                passURLFields();
            });

            $('#search').on('keyup', function () {
                passURLFields();
            });

            $("#status").change(function () {
                passURLFields();
            });

            $("#applyAdvanceFilter").change(function () {
                passURLFields();
            });

            // Dismiss handler
            KTUtil.on(document.body, '[data-kt-apply-advance-filter-dismiss="true"]', 'click', function (e) {
                var menu = KTMenu.getInstance(this);

                if (menu !== null) {
                    passURLFields();
                    return menu.dismiss(this, e);
                }
            });

            $("#exportBttn").click(function () {
                exportFieldSet();
            });
        });
    </script>
@endsection

@section('heading_right_space')

@endsection

@section('content')

            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('publisher.dashboard') }}"><i
                                            class="ri-home-5-line text-primary"></i></a></li>
                                <li class="breadcrumb-item"><a href="">Finance</a></li>
                                <li class="breadcrumb-item"><a href="">Overview</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            @include("partial.alert")
            <div class="d-flex justify-content-end align-items-center flex-wrap gap-3 mb-4">
                <!-- Right Side: Filter + Export -->
                <div class="card-toolbar d-flex align-items-center gap-3">

                    <!--begin::Daterangepicker-->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1 filter-toggle"
                            type="button" data-bs-toggle="dropdown" aria-expanded="false"
                            style="min-width: 90px; transition: none;">
                            <i class="ri-filter-3-line"></i>
                            <span>Filter</span>
                        </button>

                        <div class="dropdown-menu p-4 shadow-sm" style="min-width: 320px;">
                            <div class="mb-3">
                                <label for="pr_report_datepicker" class="form-label fw-semibold small">Date Range</label>
                                <input class="form-control form-control-sm h-auto py-1" name="date"
                                    id="pr_report_datepicker" placeholder="Pick date range" />
                            </div>
                        </div>
                    </div>
                    <!--end::Daterangepicker-->

                    <!--begin::Export-->
                    <button type="button" id="exportBttn" class="btn btn-sm btn-outline-success" data-bs-toggle="modal"
                        data-bs-target="#kt_modal_add_auth_app">
                        <i class="ri-upload-2-line"></i>
                        Export
                    </button>
                    <!--end::Export-->

                </div>
            </div>
            <!--begin::Modal - Adjust Balance-->
            <div class="modal fade" id="kt_modal_add_auth_app" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h2 class="fw-bold">
                                Export Transaction Data
                                <div class="fs-7 fw-semibold text-muted">After your request is completed, the formatted file
                                    you requested will be available for download in the <b>Tools > Download Export Files</b>
                                    section.</div>
                            </h2>
                            <!--end::Modal title-->
                            <!--begin::Close-->
                            <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                                <i class="ki-duotone ki-cross fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <!--end::Close-->
                        </div>
                        <!--end::Modal header-->
                        <!--begin::Modal body-->
                        <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                            <!--begin::Form-->
                            <form class="form w-100" novalidate="novalidate" id="kt_advertiser_export_in_form"
                                action="{{ route('publisher.generate-export-transaction') }}" method="post">
                                @csrf
                                <input type="hidden" id="totalExport" name="total" value="{{ $count }}">
                                <input type="hidden" name="search" id="search_export">
                                <input type="hidden" name="status" id="status_export">
                                <input type="hidden" name="region" id="region_export">
                                <input type="hidden" name="advertiser" id="advertiser_export">
                                <input type="hidden" name="date" id="date_export">

                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <!--begin::Label-->
                                    <label class="required fs-6 fw-semibold form-label mb-2">Select Export Format:</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <select name="export_format" data-control="select2" data-placeholder="Select a format"
                                        data-hide-search="true" class="form-select form-select-solid fw-bold">
                                        <option></option>
                                        <option value="csv">CSV</option>
                                    </select>
                                    <!--end::Input-->
                                </div>
                                <!--end::Input group-->
                                <!--begin::Actions-->
                                <div class="text-center">
                                    <button type="reset" class="btn btn-light me-3"
                                        data-kt-users-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-light-primary" id="kt_advertiser_export_submit"
                                        data-kt-users-modal-action="submit">
                                        <span class="indicator-label">Request to Export Data</span>
                                        <span class="indicator-progress">Please wait...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </form>
                            <!--end::Form-->
                        </div>
                        <!--end::Modal body-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <div class="row mt-4 mt-md-0 mt-sm-0">
                <div class="col-lg-4 col-4 mb-md-4 mb-sm-4 mb-xs-4">
                    <div class="card">
                        <span class="mask bg-white opacity-10"></span>
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-8 text-start">
                                    <div class="bg-primary-light border-radius-2xl d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="ri-money-dollar-circle-fill text-primary ri-2x"></i>
                                    </div>

                                    <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                        {{ \App\Helper\Methods::numberFormatShort($totalTransactions) }}
                                    </h5>
                                    <span class="text-light text-md fw-bold ">Total Transactions</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-4 mb-md-4 mb-sm-4 mb-xs-4">
                    <div class="card">
                        <span class="mask bg-white opacity-10"></span>
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-8 text-start">
                                    <div class="bg-green-light border-radius-2xl d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="ri-money-dollar-circle-fill text-green ri-2x"></i>
                                    </div>

                                    <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                        ${{ \App\Helper\Methods::numberFormatShort($totalSalesAmount) }}
                                    </h5>
                                    <span class="text-light text-md fw-bold ">Total Sales</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-4 mb-md-4 mb-sm-4 mb-xs-4">
                    <div class="card">
                        <span class="mask bg-white opacity-10"></span>
                        <div class="card-body p-3 position-relative">
                            <div class="row">
                                <div class="col-8 text-start">
                                    <div class="bg-yellow-light border-radius-2xl d-flex align-items-center justify-content-center"
                                        style="width: 50px; height: 50px;">
                                        <i class="ri-money-dollar-circle-fill text-golden ri-2x"></i>
                                    </div>

                                    <h5 class="text-dark font-weight-bolder mb-0 mt-3">
                                        ${{ \App\Helper\Methods::numberFormatShort($totalCommissionAmount) }}
                                    </h5>
                                    <span class="text-light text-md fw-bold ">Commission Earned</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative flex-grow-1 me-5" style="max-width: 300px;">
                            <i class="ri-search-line text-muted position-absolute ms-3"></i>
                            <input type="text"
                                id="search"
                                data-kt-ecommerce-order-filter="search"
                                class="form-control-sm ps-5"
                                placeholder="Search by ID, name, etc..." />
                        </div>

                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_views_export" class="d-none"></div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->

                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">

                    <div id="transaction-container">
                        @include("publisher.payments.ajax", compact('data'))
                    </div>

                    <div class="row mt-3">
                        <div class="col-12 col-md-6 d-flex align-items-center justify-content-between">
                            <div class="dataTables_length" id="kt_project_users_table_length">
                                <label>
                                    <select name="per_page" id="per-page-select"
                                        class="form-select-sm">
                                        <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ empty(request('per_page')) || request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->

@endsection
