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

@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="#"><i data-feather="home"></i></a>
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
    <div class="d-flex justify-content-end align-items-center gap-3 flex-wrap">
        <div class="col-3 mb-3 pl-0">
            <input class="form-control py-1" name="date" id="at_report_datepicker" placeholder="Pick date range" />
        </div>

        <div class="col-3 mb-3 px-0">
            <select class="form-control text-muted" id="status" data-control="select2" data-hide-search="true"
                data-placeholder="Status">
                <option value="" selected disabled>Status</option>
                <option value="all">All</option>
                <option value="{{ \App\Models\Transaction::STATUS_PENDING }}"
                    @if(\App\Models\Transaction::STATUS_PENDING == request()->status) selected @endif>Pending</option>
                <option value="{{ \App\Models\Transaction::STATUS_APPROVED }}"
                    @if(\App\Models\Transaction::STATUS_APPROVED == request()->status) selected @endif>Approved</option>
                <option value="{{ \App\Models\Transaction::STATUS_DECLINED }}"
                    @if(\App\Models\Transaction::STATUS_DECLINED == request()->status) selected @endif>Declined</option>
                <option value="{{ \App\Models\Transaction::STATUS_PAID }}"
                    @if(\App\Models\Transaction::STATUS_PAID == request()->status) selected @endif>Paid</option>
                <option value="{{ \App\Models\Transaction::STATUS_PENDING_PAID }}"
                    @if(\App\Models\Transaction::STATUS_PENDING_PAID == request()->status) selected @endif>Pending Paid
                </option>
            </select>
        </div>


    </div>






    @include("partial.alert")

    <div class="row mt-4 mt-md-0 mt-sm-0">
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="stat-card income">
                <div class="card-body">
                    <div class="stat-value">{{ \App\Helper\Methods::numberFormatShort($totalTransactions) }}</div>
                    <div class="stat-label">Total Transactions</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="stat-card expense">
                <div class="card-body">
                    <div class="stat-value">${{ \App\Helper\Methods::numberFormatShort($totalSalesAmount) }}</div>
                    <div class="stat-label">Total Sales</div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-12 col-12">
            <div class="stat-card balance">
                <div class="card-body">
                    <div class="stat-value">${{ \App\Helper\Methods::numberFormatShort($totalCommissionAmount) }}</div>
                    <div class="stat-label">Total Commission Earned</div>
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
                <input type="text" id="search" class="form-control form-control-sm pl-5"
                    placeholder="Search by ID, name, etc..." />
            </div>

            <div class="mb-3 d-flex align-items-end">
                <button type="button" id="exportBttn" data-toggle="modal" data-target="#kt_modal_add_auth_app"
                    class="btn btn-outline-success w-100">
                    <i class="fas fa-upload"></i> Export
                </button>
            </div>
            <div class="modal fade" id="kt_modal_add_auth_app" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header d-flex justify-content-between align-items-start">
                            <div>
                                <h5 class="modal-title font-weight-bold mb-1">Export Transaction Data</h5>
                                <small class="text-muted">
                                    After your request is completed, the formatted file you requested will be available for
                                    download in the
                                    <b>Tools > Download Export Files</b> section.
                                </small>
                            </div>
                            <button type="button" class="close btn btn-sm bg-danger ml-3" data-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true" class="text-lg text-white">&times;</span>
                            </button>
                        </div>

                        <form class="w-100" id="kt_advertiser_export_in_form"
                            action="{{ route('publisher.generate-export-transaction') }}" method="post" novalidate>
                            @csrf

                            <div class="modal-body mx-auto w-100" style="max-width: 500px;">
                                <input type="hidden" id="totalExport" name="total" value="{{ $transactions->total() }}">
                                <input type="hidden" name="search" id="search_export">
                                <input type="hidden" name="status" id="status_export">
                                <input type="hidden" name="region" id="region_export">
                                <input type="hidden" name="advertiser" id="advertiser_export">
                                <input type="hidden" name="date" id="date_export">

                                <div class="form-group">
                                    <label for="export_format" class="small font-weight-bold">Select Export Format:</label>
                                    <select name="export_format" id="export_format" class="form-control select2"
                                        data-placeholder="Select a format" data-hide-search="true">
                                        <option></option>
                                        <option value="csv">CSV</option>
                                    </select>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="reset" class="btn btn-light mr-2">Discard</button>
                                <button type="submit" class="btn btn-outline-success">
                                    Request to Export Data
                                </button>
                            </div>
                        </form>
                    </div>
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
                <div class="col-12 col-md-6 d-flex align-items-center justify-content-between">
                    <div class="dataTables_length" id="kt_project_users_table_length">
                        <label>
                            <select name="per_page" id="per-page-select" class="form-select-sm">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ empty(request('per_page')) || request('per_page') == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                            </select>
                        </label>
                    </div>
                    <div class="dataTables_info text-sm" id="kt_project_users_table_info" role="status" aria-live="polite">
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
