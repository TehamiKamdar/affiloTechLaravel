@extends('layouts.publisher.layout')

@section('styles')

    <style>
        .filter-toggle.btn {
            min-width: 90px;
            transition: none;
        }

        .filter-toggle.btn:focus,
        .filter-toggle.btn:active,
        .filter-toggle.btn.show {
            box-shadow: none !important;
            outline: none !important;
            background-color: transparent !important;
            color: #02c0ce !important;
            border-color: #02c0ce !important;
            /* Or whatever your border is */
        }

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
<script src="{{ \App\Helper\Methods::staticAsset('assets/js/scripts.bundle.js') }}"></script>
    <script>
        let adChartInstance = null;

        window.advertiserperformancegraph = function (type) {
            let date = $('input[name="date"]').val();

            fetch('/publisher/advertiser-performance-graph', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    color: "#0081fa",
                    date: date
                })
            })
                .then(response => response.json())
                .then(data => {
                    // Update button background colors
                    $('#transaction, #commission, #sales').css('backgroundColor', 'white');
                    $('#' + type).css('backgroundColor', '#e4e0e0');

                    // Prepare data
                    const currentMonthLabels = data.currentMonth.map(item => item.date);
                    const currentMonthData = data.currentMonth.map(item => item.total);

                    const previousMonthLabels = data.previousMonth.map(item => item.date);
                    const previousMonthData = data.previousMonth.map(item => item.total);

                    // Destroy previous chart if exists
                    if (adChartInstance) {
                        adChartInstance.destroy();
                    }

                    const options = {
                        chart: {
                            type: 'line',
                            height: 350,
                            toolbar: {
                                show: false
                            },
                            zoom: {
                                enabled: false
                            }
                        },
                        series: [
                            {
                                name: "Current Month",
                                data: currentMonthData
                            },
                            {
                                name: "Previous Month",
                                data: previousMonthData
                            }
                        ],
                        colors: [data.color, '#cccccc'],
                        stroke: {
                            width: [3, 2],
                            dashArray: [0, 5]
                        },
                        xaxis: {
                            categories: currentMonthLabels,
                            title: {
                                text: 'Date',
                                style: {
                                    color: '#000',
                                    fontSize: '16px',
                                    fontWeight: 600
                                }
                            }
                        },
                        yaxis: {
                            title: {
                                text: data.type,
                                style: {
                                    color: '#000',
                                    fontSize: '16px',
                                    fontWeight: 600
                                }
                            },
                            min: 0
                        },
                        legend: {
                            position: 'top'
                        },
                        responsive: [{
                            breakpoint: 600,
                            options: {
                                chart: {
                                    height: 300
                                }
                            }
                        }]
                    };

                    adChartInstance = new ApexCharts(document.querySelector("#advertiserPerformance"), options);
                    adChartInstance.render();
                })
                .catch(error => {
                    console.error("Error fetching advertiser performance graph:", error);
                });
        };
    </script>

    <script>

        $(document).ready(function () {
            advertiserperformancegraph('transaction');
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
                    status: $('#status').val(),
                    region: $('#region').val(),
                    advertiser: $('#advertiser').val(),
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
                    status: $('#status').val(),
                    region: $('#region').val(),
                    advertiser: $('#advertiser').val(),
                    sub_id: $('#sub_id').is(":checked")
                };

                $.ajax({
                    url: '{{ route("publisher.advertiser-performance") }}',
                    data: data,
                    success: function (response) {
                        console.log(`Showing ${response.from} to ${response.to} of ${response.total} entries`)
                        console.log(response.pagination)
                        $('#totaltransaction').html(numberFormatShort(Number(response.total_transactions) || 0));
                        $('#totalsales').html(numberFormatShort(Number(response.total_sales_amount) || 0));
                        $('#totalcommission').html(numberFormatShort(Number(response.total_commission_amount) || 0));
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

            let start = moment().startOf("month");  // Start of the current month
            let end = moment().endOf("month");      // End of the current month

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
                }
            });

            // Default date display in input (Current Month)
            $('input[name="date"]').val(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));

            // Handle apply event for the date picker
            $('input[name="date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MMM D, YYYY') + ' - ' + picker.endDate.format('MMM D, YYYY'));
                passURLFields();
                advertiserperformancegraph('transaction');
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
                console.log("Status Changed")
                passURLFields();
            });

            $("#applyAdvanceFilter").change(function () {
                passURLFields();
            });

            $("#exportBttn").click(function () {
                exportFieldSet();
            });
        });

        function numberFormatShort(num, precision = 1) {
            if (num == null || isNaN(num)) return "0"; // Handle null, undefined, or NaN values

            const thresholds = {
                1: '',
                1000: 'K',
                1000000: 'M',
                1000000000: 'B',
                1000000000000: 'T'
            };

            const keys = Object.keys(thresholds).reverse();
            for (let key of keys) {
                let divisor = parseInt(key);
                if (num >= divisor) {
                    let formatted = (num / divisor).toFixed(precision);
                    return formatted.replace(/\.0+$/, '') + thresholds[key]; // Remove unnecessary decimals
                }
            }
            return num.toFixed(precision);
        }

    </script>

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
                        <li class="breadcrumb-item"><a href="">Reportings</a></li>
                        <li class="breadcrumb-item"><a href="">Advertisers Performance</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->
    @include("partial.alert")
    <!--begin::Header Actions Row-->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

        <!-- Left Side: Click Performance -->
        <div>
            <a href="{{ route('publisher.click-performance') }}" class="btn btn-sm btn-flex btn-secondary fw-bold"
                style="font-size:15px;">
                Click Performance
            </a>
        </div>

        <!-- Right Side: Filter + Export -->
        <div class="card-toolbar d-flex align-items-center gap-3">

            <!--begin::Daterangepicker-->
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1 filter-toggle" type="button"
                    data-bs-toggle="dropdown" aria-expanded="false" style="min-width: 90px; transition: none;">
                    <i class="ri-filter-3-line"></i>
                    <span>Filter</span>
                </button>

                <div class="dropdown-menu p-4 shadow-sm" style="min-width: 320px;">
                    <div class="mb-3">
                        <label for="pr_report_datepicker" class="form-label fw-semibold small">Date Range</label>
                        <input class="form-control form-control-sm h-auto py-1" name="date" id="pr_report_datepicker"
                            placeholder="Pick date range" />
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
    <!--end::Header Actions Row-->

    <!--begin::Modal - Adjust Balance-->
            <div class="modal fade" id="kt_modal_add_auth_app" tabindex="-1" aria-hidden="true">
                <!--begin::Modal dialog-->
                <div class="modal-lg modal-dialog modal-dialog-centered mw-650px">
                    <!--begin::Modal content-->
                    <div class="modal-content">
                        <!--begin::Modal header-->
                        <div class="modal-header">
                            <!--begin::Modal title-->
                            <h4 class="fw-bold">
                                Export Transaction Data
                                <div class="fs-7 fw-semibold text-muted">After your request is completed, the formatted file
                                    you requested will be available for download in the <b>Tools > Download Export Files</b>
                                    section.</div>
                            </h4>
                            <!--end::Modal title-->
                            <div class="btn btn-sm btn-close bg-danger" data-bs-dismiss="modal"
                                data-kt-export-data-modal-action="close">
                            </div>
                        </div>
                        <!--end::Modal header-->
                        <form class="form w-100" novalidate="novalidate" id="kt_advertiser_export_in_form"
                            action="{{ route('publisher.generate-export-transaction') }}" method="post">
                            @csrf
                        <!--begin::Modal body-->
                        <div class="modal-body mx-auto w-50 scroll-y">
                            <!--begin::Form-->
                                <input type="hidden" id="totalExport" name="total" value="{{ $transactions->total() }}">
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

                            </div>
                            <!--end::Modal body-->
                            <div class="modal-footer">
                                <!--begin::Actions-->
                                <div class="text-center">
                                    <button type="reset" class="btn btn-light me-3"
                                        data-kt-users-modal-action="cancel">Discard</button>
                                    <button type="submit" class="btn btn-outline-success" id="kt_advertiser_export_submit"
                                        data-kt-users-modal-action="submit">
                                        <span class="indicator-label">Request to Export Data</span>
                                    </button>
                                </div>
                                <!--end::Actions-->
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Modal content-->
                </div>
                <!--end::Modal dialog-->
            </div>
            <!--end::Modal - New Card-->
    <div class="row mt-4 mt-md-0 mt-sm-0">
        <div class="col-lg-4 col-sm-6 col-12 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card" onclick="advertiserperformancegraph('transaction')">
                <span class="mask bg-white opacity-10"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-primary-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-money-dollar-circle-fill text-primary ri-2x"></i>
                            </div>

                            <h5 class="text-dark font-weight-bolder mb-0 mt-3" id='totaltransaction'>
                                {{ \App\Helper\Methods::numberFormatShort($totalTransactions) }}
                            </h5>
                            <span class="text-light text-md fw-bold ">Total Transactions</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-12 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card" onclick="advertiserperformancegraph('sales')">
                <span class="mask bg-white opacity-10"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-green-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-money-dollar-circle-fill text-green ri-2x"></i>
                            </div>

                            <h5 class="text-dark font-weight-bolder mb-0 mt-3" id='totalsales'>
                                {{ \App\Helper\Methods::numberFormatShort($totalSalesAmount) }}
                            </h5>
                            <span class="text-light text-md fw-bold ">Total Sales</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-sm-6 col-12 mb-md-4 mb-sm-4 mb-xs-4">
            <div class="card" onclick="advertiserperformancegraph('commission')">
                <span class="mask bg-white opacity-10"></span>
                <div class="card-body p-3 position-relative">
                    <div class="row">
                        <div class="col-8 text-start">
                            <div class="bg-yellow-light border-radius-2xl d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <i class="ri-money-dollar-circle-fill text-golden ri-2x"></i>
                            </div>

                            <h5 class="text-dark font-weight-bolder mb-0 mt-3" id='totalcommission'>
                                {{ \App\Helper\Methods::numberFormatShort($totalCommissionAmount) }}
                            </h5>
                            <span class="text-light text-md fw-bold ">Commission Earned</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Row-->


    {{-- Performance Graph Start --}}
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-body p-3">
                <div class="chart position-relative" style="overflow: hidden">
                    <div id="advertiserPerformance" class="chart-canvas" height="300"></div>
                </div>
            </div>
        </div>
    </div>
    {{-- Performance Graph End --}}

    <!--begin::Products-->
    <div class="card card-flush">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-4">
            <div class="d-flex align-items-center position-relative flex-grow-1 me-5" style="max-width: 300px;">
                <i class="ri-search-line text-muted position-absolute ms-3"></i>
                <input type="text" id="search" data-kt-ecommerce-order-filter="search" class="form-control-sm ps-5"
                    placeholder="Search by ID, name, etc..." />
            </div>

            <div class="d-flex align-items-center gap-3 flex-wrap">
                <div class="mb-3">
                    <select class="form-select form-select-sm text-muted" id="status" data-control="select2"
                        data-hide-search="true" data-placeholder="Status" style="min-width: 100%;">
                        <option value="" selected disabled>Status</option>
                        <option value="all">All</option>
                        <option value="{{ \App\Models\Transaction::STATUS_PENDING }}"
                            @if(\App\Models\Transaction::STATUS_PENDING == request()->status) selected @endif>Pending
                        </option>
                        <option value="{{ \App\Models\Transaction::STATUS_APPROVED }}"
                            @if(\App\Models\Transaction::STATUS_APPROVED == request()->status) selected @endif>
                            Approved</option>
                        <option value="{{ \App\Models\Transaction::STATUS_DECLINED }}"
                            @if(\App\Models\Transaction::STATUS_DECLINED == request()->status) selected @endif>
                            Declined</option>
                        <option value="{{ \App\Models\Transaction::STATUS_PAID }}"
                            @if(\App\Models\Transaction::STATUS_PAID == request()->status) selected @endif>Paid
                        </option>
                        <option value="{{ \App\Models\Transaction::STATUS_PENDING_PAID }}"
                            @if(\App\Models\Transaction::STATUS_PENDING_PAID == request()->status) selected @endif>
                            Pending Paid</option>
                    </select>
                </div>
            </div>
        </div>
        <!--begin::Card body-->
        <div class="card-body py-4">

            <div id="transaction-container">
                @include("publisher.reporting.ajax", compact('transactions'))
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start">
                    <div class="dataTables_length" id="kt_project_users_table_length">
                        <label>
                            <select name="per_page" id="per-page-select"
                                class="form-select form-select-sm form-select-solid">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ empty(request('per_page')) || request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </label>
                    </div>
                    <div class="dataTables_info" id="kt_project_users_table_info" role="status" aria-live="polite">
                        Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                    </div>
                </div>

                <div class="col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end"
                    id="pagination-container">
                    {{ $transactions->withQueryString()->links('partial.publisher_pagination') }}
                </div>

            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Products-->

@endsection
