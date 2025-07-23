@extends('layouts.publisher.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('publisherAssets/assets/bundles/izitoast/css/iziToast.min.css') }}">
    <style>
        .coupon-tearaway {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            font-family: 'Poppins', 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8eb 100%);
            transition: transform 0.3s ease;
        }

        .coupon-tearaway:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .coupon-main {
            background: white;
            padding: 25px;
            position: relative;
            border-bottom: 1px dashed #e0e0e0;
        }

        .coupon-discount {
            background: linear-gradient(135deg, #00a9da 0%, #5566dd 100%);
            color: white;
            font-size: 24px;
            font-weight: bold;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            position: absolute;
            top: -30px;
            right: -30px;
            box-shadow: 0 5px 15px rgba(85, 102, 221, 0.4);
        }

        .coupon-discount span:first-child {
            font-size: 28px;
            line-height: 1;
        }

        .coupon-discount span:last-child {
            font-size: 14px;
            margin-top: 2px;
        }

        .coupon-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .coupon-details h3 {
            color: #2c3e50;
            margin: 0;
            font-size: 22px;
            font-weight: 700;
        }

        .coupon-tag {
            background: #ff4757;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 10px;
            margin-left: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .coupon-description {
            color: #7f8c8d;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .coupon-dates {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .date-container {
            display: flex;
            align-items: center;
        }

        .date-label-valid {
            background: #00a9da;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            margin-right: 10px;
            text-transform: uppercase;
        }

        .date-label-expired {
            background: #dc3545;
            color: white;
            font-size: 10px;
            font-weight: bold;
            padding: 3px 8px;
            border-radius: 4px;
            margin-right: 10px;
            text-transform: uppercase;
        }

        .date-range {
            display: flex;
            align-items: center;
            color: #2c3e50;
            font-size: 13px;
            font-weight: 500;
        }

        .date-icon {
            margin-right: 8px;
            font-size: 16px;
        }

        .coupon-code-container {
            text-align: center;
        }

        .code-label {
            color: #7f8c8d;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 5px;
        }

        .coupon-code {
            background: #f1f3f5;
            padding: 10px 20px;
            border-radius: 6px;
            display: inline-block;
            font-family: 'Courier New', monospace;
            font-size: 18px;
            color: #00a9da;
            font-weight: bold;
            letter-spacing: 1px;
            border: 1px dashed #00a9da;
        }

        .tear-away {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .perforation {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background:
                linear-gradient(to right, white 0%, white 50%, transparent 50%, transparent 100%),
                linear-gradient(to right, #ccc 0%, #ccc 100%);
            background-size: 10px 2px, 100% 1px;
            background-repeat: repeat-x, no-repeat;
            background-position: 0 0, 0 4px;
        }

        .coupon-btn {
            background: linear-gradient(135deg, #00a9da 0%, #5566dd 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 15px rgba(85, 102, 221, 0.3);
        }

        .coupon-btn svg {
            transition: transform 0.3s;
        }

        .coupon-btn:hover {
            background: linear-gradient(135deg, #5566dd 0%, #00a9da 100%);
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(85, 102, 221, 0.4);
        }

        .coupon-btn:hover svg {
            transform: translateX(3px);
        }

        .coupon-btn:disabled,
        .coupon-btn.disabled {
            background: linear-gradient(135deg, #cccccc 0%, #999999 100%);
            color: #f0f0f0;
            cursor: not-allowed;
            box-shadow: none;
            transform: none !important;
            opacity: 0.8;
        }

        .coupon-btn:disabled svg,
        .coupon-btn.disabled svg {
            opacity: 0.5;
            transform: none !important;
        }

        .coupon-btn:disabled:hover,
        .coupon-btn.disabled:hover {
            background: linear-gradient(135deg, #cccccc 0%, #999999 100%);
            box-shadow: none;
        }
    </style>
@endsection

@section('scripts')
    <!-- JS Libraies -->
    <script src="{{ asset('publisherAssets/assets/bundles/izitoast/js/iziToast.min.js') }}"></script>
    <script>



        $(document).ready(function () {

            $('.coupon-card').each(function () {
                const $card = $(this);
                const $button = $card.find('.coupon-btn');
                const $code = $card.find('.coupon-code');

                if ($button.length && $code.length) {
                    $button.on('click', function () {
                        const couponText = $.trim($code.text());
                        console.log(couponText);
                        if (!couponText) return;

                        navigator.clipboard.writeText(couponText).then(function () {
                            iziToast.info({
                                title: 'Copied!',
                                message: `"${couponText}" has been copied to clipboard.`,
                                position: 'topRight'
                            });
                        }).catch(function (err) {
                            iziToast.error({
                                title: 'Error',
                                message: 'Failed to copy the coupon code.',
                                position: 'topRight'
                            });
                        });
                    });
                }
            });

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

@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="#"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Promotional</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">Coupons</a>
        </li>
    </ol>
@endsection

@section('content')
    @include("partial.alert")

    <div class="card card-flush">
        <div class="card-header border-0 align-items-center pt-4 pb-2 gap-2 gap-md-5">
            <div class="card-title w-100">
                <div class="d-flex justify-content-between align-items-center my-1">
                    <div class="position-relative">
                        <i class="ki-duotone ki-magnifier fs-2 position-absolute top-50 translate-middle-y ms-4">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                        <input type="text" id="search" data-kt-ecommerce-order-filter="search"
                            class="form-control form-control-solid ps-12 w-250px" placeholder="Search by id, name etc..." />
                    </div>

                    <div class="text-sm text-end">
                        Showing {{ $from }} to {{ $to }} of {{ $total }} entries
                    </div>
                </div>

                <div id="kt_ecommerce_report_views_export" class="d-none">

                </div>
            </div>


        </div>
        <div class="card-body py-4">

            <div id="transaction-container">
                @include("publisher.promote.ajax", compact('coupons'))
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

                </div>

                <div class="col-12 col-md-6 d-flex align-items-center justify-content-end" id="pagination-container">
                    {{ $coupons->withQueryString()->links('partial.publisher_pagination') }}
                </div>

            </div>
        </div>
    </div>

@endsection
