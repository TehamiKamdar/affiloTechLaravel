@extends('layouts.publisher.layout')

@section('styles')
    <link rel="stylesheet" href="{{ asset('publisherAssets/assets/bundles/izitoast/css/iziToast.min.css') }}">
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
    <script src="{{ asset('publisherAssets/assets/bundles/izitoast/js/iziToast.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.copy-link-btn').on('click', function () {
                const $row = $(this).closest('tr'); // Go to the table row
                const $link = $row.find('.deep-link'); // Find the link inside it
                const url = $link.attr('href');

                if (!url) return;

                navigator.clipboard.writeText(url).then(function () {
                    iziToast.success({
                        title: 'Copied!',
                        message: 'Deep URL copied to clipboard.',
                        position: 'topRight'
                    });
                }).catch(function () {
                    iziToast.error({
                        title: 'Error',
                        message: 'Failed to copy the URL.',
                        position: 'topRight'
                    });
                });
            });
            // Initialize tooltips
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
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
                    url: '{{ route("publisher.deep-links") }}',
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
            <a href="#" class="text-sm">Deep Links</a>
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
                @include("publisher.promote.deep-link-ajax", compact('links'))
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
                    {{ $links->withQueryString()->links('partial.publisher_pagination') }}
                </div>

            </div>
        </div>
    </div>
    <script>
        function copyLink(link) {
            let text = link;
            let message = 'Link Copied Successfully!'
            var tempInput = document.createElement("textarea");
            tempInput.value = text;
            document.body.appendChild(tempInput);
            tempInput.select();
            document.execCommand("copy");
            document.body.removeChild(tempInput);

            if (message) {
                normalMsg({ "message": message, "success": true });
            }
        }
    </script>
@endsection
