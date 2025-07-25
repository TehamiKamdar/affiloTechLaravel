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
            border-radius: 8px !important;
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

        .ml-3 {
            margin-left: 10px;
        }

        .display-hidden {
            display: none;
        }
    </style>

@endsection

@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="#"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Finance</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">Overview</a>
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

@section('content')
    @include("partial.alert")
    <div class="d-flex justify-content-end align-items-center flex-wrap gap-3 mb-4">
        <div class="card-toolbar d-flex align-items-center gap-3">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1 filter-toggle" type="button"
                    data-toggle="dropdown" aria-expanded="false" style="min-width: 90px; transition: none;">
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

            <button type="button" id="exportBttn" class="btn btn-sm btn-outline-success" data-toggle="modal"
                data-target="#kt_modal_add_auth_app">
                <i class="ri-upload-2-line"></i>
                Export
            </button>

        </div>
    </div>
    <div class="modal fade" id="kt_modal_add_auth_app" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-header">
                <h2 class="fw-bold">
                    Export Transaction Data
                    <div class="fs-7 fw-semibold text-muted">After your request is completed, the formatted file
                        you requested will be available for download in the <b>Tools > Download Export Files</b>
                        section.</div>
                </h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-kt-users-modal-action="close">
                    <i class="ki-duotone ki-cross fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <form class="form w-100" novalidate="novalidate" id="kt_advertiser_export_in_form"
                    action="{{ route('publisher.generate-export-transaction') }}" method="post">
                    @csrf
                    <input type="hidden" id="totalExport" name="total" value="{{ $count }}">
                    <input type="hidden" name="search" id="search_export">
                    <input type="hidden" name="status" id="status_export">
                    <input type="hidden" name="region" id="region_export">
                    <input type="hidden" name="advertiser" id="advertiser_export">
                    <input type="hidden" name="date" id="date_export">

                    <div class="fv-row mb-10">
                        <label class="required fs-6 fw-semibold form-label mb-2">Select Export Format:</label>
                        <select name="export_format" data-control="select2" data-placeholder="Select a format"
                            data-hide-search="true" class="form-select form-select-solid fw-bold">
                            <option></option>
                            <option value="csv">CSV</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <button type="reset" class="btn btn-light me-3" data-kt-users-modal-action="cancel">Discard</button>
                        <button type="submit" class="btn btn-light-primary" id="kt_advertiser_export_submit"
                            data-kt-users-modal-action="submit">
                            <span class="indicator-label">Request to Export Data</span>
                            <span class="indicator-progress">Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <section class="section">
        <div class="row mt-4 mt-md-0 mt-sm-0">
            <!-- Total Transactions -->
            <div class="col-md-6 col-lg-3">
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
            <div class="col-md-6 col-lg-3">
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
            <div class="col-md-6 col-lg-3">
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

            <!-- Total Clicks -->
            <div class="col-md-6 col-lg-3">
                <div class="metric-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Clicks</span>
                        <i class="fas fa-mouse-pointer text-primary"></i>
                    </div>
                    <div class="card-body">
                        <div class="metric-value">{{ \App\Helper\Methods::numberFormatShort($totalClicks) }}</div>
                        <div class="metric-label">Total Clicks</div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="card card-flush">
        <div class="card-header border-0 pt-4">
            <div class="card-title">
                <div class="d-flex align-items-center position-relative flex-grow-1 me-5" style="max-width: 300px;">
                    <i class="ri-search-line text-muted position-absolute ms-3"></i>
                    <input type="text" id="search" data-kt-ecommerce-order-filter="search" class="form-control ps-5"
                        placeholder="Search by ID, name, etc..." />
                </div>

                <div id="kt_ecommerce_report_views_export" class="d-none"></div>
            </div>

        </div>
        <div class="card-body py-4">

            <div id="transaction-container">
                @include("publisher.payments.ajax", compact('data'))
            </div>

            <div class="row mt-3">
                <div class="col-12 col-md-6 d-flex align-items-center justify-content-between">
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
                </div>
            </div>
        </div>
    </div>

@endsection
