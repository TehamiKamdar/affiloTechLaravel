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

@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Reporting</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">Transactions</a>
        </li>
    </ol>
@endsection

@section('scripts')

    <script>

        $(document).ready(function () {

            const exportFieldSet = () => {
                $("#search_export").val($('#search').val());
                $("#status_export").val($("#status").val());
                $("#region_export").val($("#region").val());
                $("#advertiser_export").val($("#advertiser").val());
                $("#date_export").val($('#at_report_datepicker').val());
            }

            const passURLFields = () => {
                updateAndFetch({
                    per_page: $('#per-page-select').val(),
                    page: 1,
                    search: $('#search').val(),
                    date: $('#at_report_datepicker').val(),
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
                    date: $('#at_report_datepicker').val(),
                    status: $('#status').val(),
                    region: $('#region').val(),
                    advertiser: $('#advertiser').val(),
                    sub_id: $('#sub_id').is(":checked")
                };

                $.ajax({
                    url: '{{ route("publisher.transactions") }}',
                    data: data,
                    success: function (response) {
                        console.log(`Showing ${response.from} to ${response.to} of ${response.total} entries`)
                        $('#totaltransaction').html(numberFormatShort(Number(response.total_transactions) || 0));
                        $('#totalsales').html(numberFormatShort(Number(response.total_sales_amount) || 0));
                        $('#totalcommission').html(numberFormatShort(Number(response.total_commission_amount) || 0));
                        $('#transaction-container').html(response.data);
                        $('#transaction-container').html(response.data);
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

            const urlParams = new URLSearchParams(window.location.search);
            const dateRangeParam = urlParams.get('date'); // e.g., "Jan 1, 2024 - Jun 30, 2024"

            if (dateRangeParam) {
                setDateRangeFromCustomURL(dateRangeParam);
            }

            function setDateRangeFromCustomURL(dateRange) {
                const dates = dateRange.split(" - ");
                if (dates.length === 2) {
                    const parsedStart = moment(dates[0], "MMM D, YYYY");
                    const parsedEnd = moment(dates[1], "MMM D, YYYY");

                    if (parsedStart.isValid() && parsedEnd.isValid()) {
                        start = parsedStart;
                        end = parsedEnd;
                    }
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
                    format: "MMM D, YYYY",
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

            // Set initial input value
            $('input[name="date"]').val(start.format('MMM D, YYYY') + ' - ' + end.format('MMM D, YYYY'));

            // Apply button updates input
            $('input[name="date"]').on('apply.daterangepicker', function (ev, picker) {
                $(this).val(picker.startDate.format('MMM D, YYYY') + ' - ' + picker.endDate.format('MMM D, YYYY'));
                passURLFields(); // Custom function: likely pushes data to URL or refreshes
            });

            // Cancel button clears input
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

@section('heading_right_space')
    <!--begin::Actions-->

    <!--end::Actions-->
@endsection

@section('content')

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header">
            <h4>Advanced Filters</h4>
        </div>
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-12 col-lg-6 mb-3">
                    <label for="at_report_datepicker">Select Date</label>
                    <input class="form-control py-1" name="date" id="at_report_datepicker" placeholder="Pick date range" />
                </div>

                <div class="col-12 col-lg-6 mb-3">
                    <label for="status">Select Status</label>
                    <select class="form-control text-muted" id="status" data-control="select2" data-hide-search="true"
                        data-placeholder="Status">
                        <option value="" selected disabled>Status</option>
                        <option value="all">All</option>
                        <option value="{{ \App\Models\Transaction::STATUS_PENDING }}"
                            @if(\App\Models\Transaction::STATUS_PENDING == request()->status) selected @endif>Pending</option>
                        <option value="{{ \App\Models\Transaction::STATUS_APPROVED }}"
                            @if(\App\Models\Transaction::STATUS_APPROVED == request()->status) selected @endif>Approved
                        </option>
                        <option value="{{ \App\Models\Transaction::STATUS_DECLINED }}"
                            @if(\App\Models\Transaction::STATUS_DECLINED == request()->status) selected @endif>Declined
                        </option>
                        <option value="{{ \App\Models\Transaction::STATUS_PAID }}"
                            @if(\App\Models\Transaction::STATUS_PAID == request()->status) selected @endif>Paid</option>
                        <option value="{{ \App\Models\Transaction::STATUS_PENDING_PAID }}"
                            @if(\App\Models\Transaction::STATUS_PENDING_PAID == request()->status) selected @endif>Pending
                            Paid
                        </option>
                    </select>
                </div>
            </div>
        </div>



    </div>






    @include("partial.alert")

    <div class="row mt-4 mt-md-0 mt-sm-0">
        <!-- Total Transactions -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="metric-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Transactions</span>
                    <i class="fas fa-exchange-alt text-primary"></i>
                </div>
                <div class="card-body">
                    <div class="metric-value">{{ \App\Helper\Methods::numberFormatShort($totalTransactions) }}</div>
                    <div class="metric-label">Total Transactions</div>
                </div>
            </div>
        </div>

        <!-- Total Sales -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="metric-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Sales</span>
                    <i class="fas fa-shopping-cart text-primary"></i>
                </div>
                <div class="card-body">
                    <div class="metric-value">${{ \App\Helper\Methods::numberFormatShort($totalSalesAmount) }}</div>
                    <div class="metric-label">Total Sales</div>
                </div>
            </div>
        </div>

        <!-- Total Commission -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="metric-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>Commission</span>
                    <i class="fas fa-hand-holding-usd text-primary"></i>
                </div>
                <div class="card-body">
                    <div class="metric-value">${{ \App\Helper\Methods::numberFormatShort($totalCommissionAmount) }}
                    </div>
                    <div class="metric-label">Total Commission</div>
                </div>
            </div>
        </div>
    </div>

    <!--begin::Products-->
    <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-4">
            <div class="d-flex align-items-center position-relative flex-grow-1 mr-3" style="max-width: 300px;">
                <i class="fas fa-search text-muted position-absolute ml-3" style="z-index: 1;"></i>
                <input type="text" id="search" class="form-control pl-5"
                    placeholder="Search by ID, name, etc..." />
            </div>

            <div class="mb-3 d-flex align-items-end">
                <button type="button" id="exportBttn" data-toggle="modal" data-target="#kt_modal_add_auth_app"
                    class="btn btn-success">
                    <i class="fas fa-file-export mr-1"></i> Export as CSV
                </button>
            </div>

        </div>
        <div class="modal fade" id="kt_modal_add_auth_app" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header d-flex justify-content-between align-items-start">
                        <div>
                            <h4 class="text-dark">Export Transaction Data</h4>
                        </div>
                        <button type="button" class="btn btn-sm btn-close btn-danger" data-dismiss="modal" aria-label="Close">
                            <span class="text-white text-lg">&times;</span>
                        </button>
                    </div>

                    <form class="w-100" id="kt_advertiser_export_in_form"
                        action="{{ route('publisher.generate-export-transaction') }}" method="post" novalidate>
                        @csrf

                        <div class="modal-body">
                            <div class="fw-semibold text-muted">
                                After your request is completed, the formatted file you requested will be available for
                                download in the
                                <b>Tools > Download Export Files</b> section.
                            </div>
                            <input type="hidden" id="totalExport" name="total" value="{{ $transactions->total() }}">
                            <input type="hidden" name="search" id="search_export">
                            <input type="hidden" name="status" id="status_export">
                            <input type="hidden" name="region" id="region_export">
                            <input type="hidden" name="advertiser" id="advertiser_export">
                            <input type="hidden" name="date" id="date_export">
                            <input type="hidden" name="export_format" id="export_format" value="csv">

                            {{-- <div class="form-group">
                                <label for="export_format" class="small font-weight-bold">Select Export Format:</label>
                                <select name="export_format" id="export_format" class="form-control select2"
                                    data-placeholder="Select a format" data-hide-search="true">
                                    <option></option>
                                    <option value="csv">CSV</option>
                                </select>
                            </div> --}}
                        </div>

                        <div class="modal-footer">
                            <button type="reset" class="btn btn-light mr-2" data-dismiss="modal">Discard</button>
                            <button type="submit" class="btn btn-outline-success">
                                Request to Export Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body py-4">

            <div id="transaction-container">
                @include("publisher.reporting.ajax", compact('transactions'))
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
                    {{ $transactions->withQueryString()->links('partial.publisher_pagination') }}
                </div>

            </div>
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Products-->


@endsection
