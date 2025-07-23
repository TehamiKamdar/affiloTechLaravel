@extends('layouts.publisher.layout')

@section('styles')

    <style>
        .stat-card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            transition: transform 0.3s;
            background: white;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card .card-body {
            padding: 1.5rem;
        }

        .stat-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .stat-card.income .icon {
            background-color: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .stat-card.expense .icon {
            background-color: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .stat-card.balance .icon {
            background-color: rgba(0, 169, 218, 0.1);
            color: var(--primary);
        }

        .stat-card .stat-value {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.25rem;
        }

        .stat-card .stat-label {
            color: #6c757d;
            font-size: 0.9rem;
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
        let adChartInstance = null;

        window.advertiserperformancegraph = function (type) {
            showLoader('advertiserPerformance')
            let date = $('input[name="date"]').val();

            fetch('/publisher/advertiser-performance-graph', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    type: type,
                    color: "#00a9da",
                    date: date
                })
            })
                .then(response => response.json())
                .then(data => {
                    // Reset all stat-card backgrounds
                    document.querySelectorAll('.stat-card').forEach(card => {
                        card.style.backgroundColor = 'white';
                        card.querySelector('.stat-value').style.color = '#000'; // or your default text color
                        card.querySelector('.stat-label').style.color = '#6c757d'; // or your default label color
                    });
                    // Highlight the active card
                    const targetCard = document.querySelector(`.stat-card.${type}`);
                    if (targetCard) {
                        targetCard.style.backgroundColor = '#00a9da';
                        targetCard.style.transform = 'translateY(-5px)';
                        targetCard.querySelector('.stat-value').style.color = 'white';
                        targetCard.querySelector('.stat-label').style.color = 'white';
                    }

                    // Prepare chart data
                    let chartData = [];

                    data.currentMonth.forEach((item, index) => {
                        chartData.push({
                            date: item.date,
                            current: item.total,
                            previous: data.previousMonth[index] ? data.previousMonth[index].total : null
                        });
                    });

                    // Remove previous chart if exists
                    if (window.adChartInstance) {
                        window.adChartInstance.dispose();
                    }

                    am4core.useTheme(am4themes_animated);

                    // Create chart
                    let chart = am4core.create("advertiserPerformance", am4charts.XYChart);
                    chart.data = chartData;
                    window.adChartInstance = chart;

                    // Create X axis
                    let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
                    categoryAxis.dataFields.category = "date";
                    categoryAxis.title.text = "Date";
                    categoryAxis.title.fontSize = 16;
                    categoryAxis.title.fontWeight = "600";

                    // Create Y axis
                    let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
                    valueAxis.title.text = data.type;
                    valueAxis.title.fontSize = 16;
                    valueAxis.title.fontWeight = "600";
                    valueAxis.min = 0;

                    // Current Month series
                    let currentSeries = chart.series.push(new am4charts.LineSeries());
                    currentSeries.dataFields.valueY = "current";
                    currentSeries.dataFields.categoryX = "date";
                    currentSeries.name = "Current Month";
                    currentSeries.stroke = am4core.color("#00a9da");
                    currentSeries.strokeWidth = 3;
                    currentSeries.fill = am4core.color("#d4f1f8");
                    currentSeries.fillOpacity = 0.5;
                    currentSeries.tooltipText = "{name}: [bold]{valueY}[/]";

                    // Previous Month series
                    let previousSeries = chart.series.push(new am4charts.LineSeries());
                    previousSeries.dataFields.valueY = "previous";
                    previousSeries.dataFields.categoryX = "date";
                    previousSeries.name = "Previous Month";
                    previousSeries.stroke = am4core.color("#cccccc");
                    previousSeries.strokeDasharray = "5,5";
                    previousSeries.strokeWidth = 2;
                    previousSeries.tooltipText = "{name}: [bold]{valueY}[/]";

                    // Add legend
                    chart.legend = new am4charts.Legend();
                    chart.legend.position = "top";

                    // Add cursor
                    chart.cursor = new am4charts.XYCursor();
                    chart.cursor.lineY.disabled = true;
                })
                .catch(error => {
                    console.error("Error fetching advertiser performance graph:", error);
                })
                .finally(() => {
                    hideLoader('advertiserPerformance')
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

@section ('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="#"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Reporting</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">Advertisers Performance</a>
        </li>
    </ol>
@endsection
@section('content')


    @include("partial.alert")


    <div class="row mt-4 mt-md-0 mt-sm-0">
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="stat-card transaction" onclick="advertiserperformancegraph('transaction')">
                <div class="card-body">
                    <div class="stat-value">{{ \App\Helper\Methods::numberFormatShort($totalTransactions) }}</div>
                    <div class="stat-label">Total Transactions</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="stat-card sales" onclick="advertiserperformancegraph('sales')">
                <div class="card-body">
                    <div class="stat-value">${{ \App\Helper\Methods::numberFormatShort($totalSalesAmount) }}</div>
                    <div class="stat-label">Total Sales</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="stat-card commission" onclick="advertiserperformancegraph('commission')">
                <div class="card-body">
                    <div class="stat-value">${{ \App\Helper\Methods::numberFormatShort($totalCommissionAmount) }}</div>
                    <div class="stat-label">Total Commission Earned</div>
                </div>
            </div>
        </div>

    </div>
    <!--end::Row-->


    {{-- Performance Graph Start --}}
    <div class="col-12 mb-3 p-0">
        <div class="card">
            <div class="card-body p-3">
                <div class="chart position-relative" style="overflow: hidden">
                    <div id="advertiserPerformance" style="height: 400px"></div>
                </div>
            </div>
        </div>
    </div>
    {{-- Performance Graph End --}}

    <!--begin::Products-->
    <div class="card card-flush">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap">
            <!-- Search Input -->
            <div class="d-flex align-items-center position-relative flex-grow-1 mb-2 mb-md-0" style="max-width: 300px;">
                <i class="fas fa-search text-muted position-absolute ml-3" style="z-index: 2;"></i>
                <input type="text" id="search" class="form-control form-control-sm pl-5"
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
                            <input type="text" class="form-control form-control-sm" name="date" id="pr_report_datepicker"
                                placeholder="Pick date range">
                        </div>
                        <div class="form-group mb-0">
                            <select class="form-control form-control-sm" id="status" data-control="select2"
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
                            <input type="hidden" id="totalExport" name="total" value="{{ $transactions->total() }}">
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
