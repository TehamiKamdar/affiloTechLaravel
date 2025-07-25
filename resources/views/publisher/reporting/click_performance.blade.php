@extends('layouts.publisher.layout')

@section('styles')
    <style>
        label {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        /* Modern Select Dropdown */
        .custom-select {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            height: 50px;
            padding: 0.75rem 1.25rem;
            border: 2px solid #e0e3ed;
            border-radius: 8px;
            background-color: white;
            color: var(--primary-color);
            font-weight: 500;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2300a9da' viewBox='0 0 16 16'%3E%3Cpath d='M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 1.25rem center;
            background-size: 12px;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .custom-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(103, 119, 227, 0.2);
            outline: none;
        }

        select option {
            padding: 12px 16px;
            background-color: white;
            color: var(--primary-color);
            font-weight: 500;
            border-bottom: 1px solid #f0f2fc;
        }

        option:hover {
            background-color: var(--primary-very-light) !important;
        }

        select option:checked,
        select option:active {
            background-color: var(--primary-very-light) !important;
            color: var(--primary-color);
        }
.form-control {
            height: 50px;
            border: 2px solid #e0e3ed;
            padding: 0.75rem 1.25rem;
            color: var(--primary-color);
            font-weight: 500;
            border-radius: 8px  !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }
        .metric-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 1.5rem;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        .metric-card .card-header {
            background-color: white;
            border-bottom: none;
            padding: 1rem 1.5rem;
            font-weight: 600;
            color: var(--primary);
        }

        .metric-card .card-body {
            padding: 1.5rem;
        }

        .metric-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 0.5rem;
        }

        .metric-label {
            color: #7f8c8d;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .metric-change {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-top: 0.5rem;
        }

        .metric-change.up {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .metric-change.down {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
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
    </style>
@endsection

@section('scripts')
    <script src="{{asset('publisherAssets/assets/bundles/amcharts4/core.js')}}"></script>
    <script src="{{asset('publisherAssets/assets/bundles/amcharts4/maps.js')}}"></script>
    <script src="{{asset('publisherAssets/assets/bundles/amcharts4/charts.js')}}"></script>
    <script src="{{asset('publisherAssets/assets/bundles/amcharts4/animated.js')}}"></script>
    <script src="{{asset('publisherAssets/assets/bundles/amcharts4/worldLow.js')}}"></script>
    <script>
        let clicksChartInstance = null;

        function clickGraphData(date) {
            const chartId = "clicksGraph";
            showLoader(chartId); // Show loader at start

            fetch('/publisher/clicks-data', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: "clicks",
                    color: "#0081fa",
                    date: date
                })
            })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    am4core.ready(function () {
                        // Destroy previous instance if exists
                        if (clicksChartInstance) {
                            clicksChartInstance.dispose();
                        }

                        // Create chart
                        am4core.useTheme(am4themes_animated);
                        let chart = am4core.create(chartId, am4charts.XYChart);
                        clicksChartInstance = chart;

                        chart.paddingRight = 20;

                        // Combine data
                        const chartData = data.currentMonth.map((item, index) => ({
                            date: item.date,
                            current: item.total,
                            previous: data.previousMonth[index] ? data.previousMonth[index].total : 0
                        }));

                        chart.data = chartData;

                        // X Axis
                        let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                        categoryAxis.dataFields.category = "date";
                        categoryAxis.title.text = "Date";
                        categoryAxis.title.fontSize = 16;
                        categoryAxis.title.fontWeight = "600";

                        // Y Axis
                        let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                        valueAxis.min = 0;
                        valueAxis.title.text = "Total Clicks";
                        valueAxis.title.fontSize = 16;
                        valueAxis.title.fontWeight = "600";

                        // Current Month Series
                        let series1 = chart.series.push(new am4charts.LineSeries());
                        series1.dataFields.valueY = "current";
                        series1.dataFields.categoryX = "date";
                        series1.name = "Current Month";
                        series1.strokeWidth = 3;
                        series1.stroke = am4core.color(data.color);
                        series1.fill = am4core.color(data.color);
                        series1.fillOpacity = 0.1;
                        series1.tensionX = 0.8;

                        // Previous Month Series
                        let series2 = chart.series.push(new am4charts.LineSeries());
                        series2.dataFields.valueY = "previous";
                        series2.dataFields.categoryX = "date";
                        series2.name = "Previous Month";
                        series2.strokeWidth = 2;
                        series2.strokeDasharray = "5,5";
                        series2.stroke = am4core.color("#cccccc");
                        series2.fillOpacity = 0;
                        series2.tensionX = 0.8;

                        chart.legend = new am4charts.Legend();
                        chart.legend.position = "top";

                        chart.responsive.enabled = true;

                        hideLoader(chartId); // Hide loader when done
                    });
                })
                .catch(error => {
                    console.error("Error fetching click graph data:", error);
                     // Hide loader even if there's an error
                })
                .finally(()=>{
                    hideLoader(chartId);
                });
        }
    </script>


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

                };

                $.ajax({
                    url: '{{ route("publisher.click-performance") }}',
                    data: data,
                    success: function (response) {
                        console.log(`Showing ${response.from} to ${response.to} of ${response.total} entries`)
                        console.log(response.pagination)

                        $('#total_click').html(numberFormatShort(Number(response.total_clicks) || 0));

                        $('#transaction-container').html(response.data);
                        $('#pagination-container').html(response.pagination);
                        $('#kt_project_users_table_info').html(`Showing ${response.from} to ${response.to} of ${response.total} entries`);
                        $('#total_click').val(response.total_clicks);
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

            let start = moment().startOf("month");
            let end = moment().endOf("month");

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
                clickGraphData($(this).val());
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

            $("#exportBttn").click(function () {
                exportFieldSet();
            });

            clickGraphData($('input[name="date"]').val());
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

@section ('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="#"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Reporting</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">Clicks Performance</a>
        </li>
    </ol>
@endsection
@section('content')
    @include("partial.alert")
    {{-- <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

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
                        <input class="form-control form-control h-auto py-1" name="date" id="pr_report_datepicker"
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
                        <input type="hidden" id="totalExport" name="total" value="{{ $clicks->total() }}">
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
    </div> --}}
    <!--end::Modal - New Card-->
    <!-- Total Clicks -->
            <div class="col-12 col-md-6 col-lg-4 px-0">
                <div class="metric-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Clicks</span>
                        <i class="fas fa-mouse-pointer text-primary"></i>
                    </div>
                    <div class="card-body">
                        <div class="metric-value">{{ \App\Helper\Methods::numberFormatShort($total_clicks) }}</div>
                        <div class="metric-label">Total Clicks</div>
                    </div>
                </div>
            </div>

    <div class="card mb-3">
        <div class="card-header justify-content-center">
            <h4>Clicks Statistics</h4>
        </div>
        <div class="col-12">
            <div class="clickContainer">
                <div id="clicksGraph" style="height:400px !important"></div>
            </div>
        </div>
    </div>

    <div class="card card-flush">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <!-- Search Input -->
            <div class="d-flex align-items-center position-relative flex-grow-1 mb-2 mb-md-0" style="max-width: 300px;">
                <i class="fas fa-search text-muted position-absolute ml-3" style="z-index: 2;"></i>
                <input type="text" id="search" class="form-control form-control pl-5"
                    placeholder="Search by ID, name, etc..." />
            </div>

            <!-- Filter Button & Select -->
            <div class="d-flex align-items-center flex-wrap mb-2 mb-md-0">
                <div class="dropdown mr-3">
                    <button class="btn btn-sm btn-outline-primary d-flex align-items-center" type="button"
                        data-toggle="dropdown">
                        <i class="fas fa-sort-down mr-1"></i>
                        <span>Filter</span>
                    </button>
                    <div class="dropdown-menu p-4 shadow" style="min-width: 320px;">
                        <div class="form-group mb-3">
                            <label for="pr_report_datepicker" class="small font-weight-bold">Date Range</label>
                            <input type="text" class="form-control form-control" name="date" id="pr_report_datepicker"
                                placeholder="Pick date range">
                        </div>
                        <div class="form-group mb-0">
                            <select class="form-control form-control" id="status" data-control="select2"
                                data-hide-search="true" data-placeholder="Status">
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

                <!-- Export Button -->
                <button type="button" id="exportBttn" class="btn btn-sm btn-success" data-toggle="modal"
                    data-target="#kt_modal_add_auth_app">
                    <i class="fas fa-file-export mr-1"></i> Export as CSV
                </button>
            </div>
        </div>

        <!-- Export Modal -->
        <div class="modal fade" id="kt_modal_add_auth_app" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between">
                        <h5 class="modal-title font-weight-bold mb-0">Export Transaction Data</h5>
                        <button type="button" class="close text-white bg-danger btn btn-sm ml-2" data-dismiss="modal"
                            aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <form action="{{ route('publisher.generate-export-transaction') }}" method="post"
                        id="kt_advertiser_export_in_form">
                        @csrf
                        <div class="modal-body">
                            <p class="text-muted mb-3">
                                After your request is completed, the formatted file will be available in
                                <strong>Tools > Download Export Files</strong>.
                            </p>
                            <input type="hidden" id="totalExport" name="total" value="{{ $clicks->total() }}">
                            <input type="hidden" name="search" id="search_export">
                            <input type="hidden" name="status" id="status_export">
                            <input type="hidden" name="region" id="region_export">
                            <input type="hidden" name="advertiser" id="advertiser_export">
                            <input type="hidden" name="date" id="date_export">
                            <input type="hidden" name="export_format" id="export_format" value="csv">
                        </div>
                        <div class="modal-footer">
                            <button type="reset" class="btn btn-light">Discard</button>
                            <button type="submit" class="btn btn-outline-success">Request to Export Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body py-4">

            <div id="transaction-container">
                @include("publisher.reporting.clickajax", compact('clicks'))
            </div>

            <div class="row mt-3">
                <div class="col-12 col-md-6 d-flex align-items-center justify-content-start">
                    <div class="dataTables_length" id="kt_project_users_table_length">
                        <label>
                            <select name="per_page" id="per-page-select" class="form-control">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ empty(request('per_page')) || request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </label>
                    </div>
                    <div class="dataTables_info text-sm ml-2" id="kt_project_users_table_info" role="status" aria-live="polite">
                        Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                    </div>
                </div>

                <div class="col-12 col-md-6 d-flex align-items-center justify-content-end" id="pagination-container">
                    {{ $clicks->withQueryString()->links('partial.publisher_pagination') }}
                </div>

            </div>
        </div>
    </div>
@endsection
