@extends('layouts.publisher.layout')

@section('styles')
    <style>
        .coupon-card {
            transition: transform 0.3s ease;
            margin-bottom: 20px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .coupon-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }


        .coupon-value {
            font-size: 1.75rem;
            font-weight: bold;
        }

        .coupon-details {
            color: #6c757d;
            font-size: 0.9rem;
        }


        .ribbon {
            position: absolute;
            top: 2px;
            right: -30px;
            background-color: #dc3545;
            color: white;
            padding: 5px 35px;
            transform: rotate(45deg);
            font-size: 0.75rem;
            font-weight: bold;
        }

        .ribbon-limited {
            position: absolute;
            top: 10px;
            right: -30px;
            background-color: #dc3545;
            color: white;
            padding: 5px 30px;
            transform: rotate(45deg);
            font-size: 0.75rem;
            font-weight: bold;
        }
    </style>

@endsection

@section('scripts')
<script>
    $(document).on('click', '.copy-btn', function () {
            let button = $(this);
            let code = button.data('code');

            copyToClipboard(code).then(() => {
                button.removeClass('btn-primary').addClass('btn-success');
                button.html('<i class="fas fa-check me-1"></i> Copied!');

                setTimeout(() => {
                    button.removeClass('btn-success').addClass('btn-primary');
                    button.html('<i class="fas fa-copy me-1"></i> Copy Code');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy code:', err);
            });
        });


        function copyToClipboard(text) {
            if (navigator.clipboard) {
                return navigator.clipboard.writeText(text);
            } else {
                let textarea = document.createElement("textarea");
                textarea.value = text;
                textarea.style.position = "fixed";  // Avoid scrolling
                document.body.appendChild(textarea);
                textarea.focus();
                textarea.select();
                try {
                    document.execCommand('copy');
                } catch (err) {
                    console.error('Fallback: Copy command failed', err);
                }
                document.body.removeChild(textarea);
                return Promise.resolve(); // for consistent handling
            }
        }
</script>
    <script>

        $(document).ready(function () {

            const exportFieldSet = () => {
                $("#search_export").val($('#search').val());
            }

            const passURLFields = () => {
                updateAndFetch({
                    per_page: $('#per-page-select').val(),
                    page: 1,
                    search: $('#search').val(),
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
                };

                $.ajax({
                    url: '{{ route("publisher.coupons") }}',
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

            $("#exportBttn").click(function () {
                exportFieldSet();
            });
        });
    </script>
@endsection

@section('heading_right_space')
    <!--begin::Actions-->
    <div class="d-flex align-items-center gap-2 gap-lg-3" style="font-size:16px">
        Total Results:<span style="font-weight:900">{{$total}}</span>
        <!--begin::Secondary button-->
        <!--end::Secondary button-->
    </div>
    <!--end::Actions-->
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
                                <li class="breadcrumb-item"><a href="">Promotions</a></li>
                                <li class="breadcrumb-item"><a href="">Coupons</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ breadcrumb ] end -->
            @include("partial.alert")



            <!--begin::Products-->
            <div class="card card-flush">
                <!--begin::Card header-->
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <!--begin::Card title-->
                    <div class="card-title">
                        <!--begin::Search-->
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-duotone ki-magnifier fs-2 position-absolute ms-4">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                            <input type="text" id="search" data-kt-ecommerce-order-filter="search"
                                class="form-control form-control-solid w-250px ps-12"
                                placeholder="Search by id, name etc..." />
                        </div>
                        <!--end::Search-->
                        <!--begin::Export buttons-->
                        <div id="kt_ecommerce_report_views_export" class="d-none">

                        </div>
                        <!--end::Export buttons-->
                    </div>
                    <!--end::Card title-->
                    <!--begin::Card toolbar-->

                    <!--end::Card toolbar-->

                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body py-4">

                    <div id="transaction-container">
                        @include("publisher.promote.ajax", compact('coupons'))
                    </div>

                    <div class="row mt-3">
                        <div
                            class="col-12 col-md-6 d-flex align-items-center justify-content-between">
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
                            <div class="dataTables_info text-sm" id="kt_project_users_table_info" role="status" aria-live="polite">
                                Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                            </div>
                        </div>

                        <div class="col-12 col-md-6 d-flex align-items-center justify-content-end"
                            id="pagination-container">
                            {{ $coupons->withQueryString()->links('partial.publisher_pagination') }}
                        </div>

                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Products-->

@endsection
