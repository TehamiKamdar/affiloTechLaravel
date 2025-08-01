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
            z-index: 1000; /* Ensure loader is above other content */
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
        <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
    </li>
    <li class="breadcrumb-item mt-1">
        <a href="#" class="text-sm">Finance</a>
    </li>
    <li class="breadcrumb-item mt-1 active">
        <a href="#" class="text-sm">Payments</a>
    </li>
</ol>
@endsection

@section('scripts')

    <script>

        $(document).ready(function() {

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
                    search: $('#search_by_name').val(),
                };

                $.ajax({
                    url: '{{ route("publisher.payments") }}',
                    data: data,
                    success: function(response) {
                        console.log(`Showing ${response.from} to ${response.to} of ${response.total} entries`)
                        console.log(response.pagination)
                        $('#transaction-container').html(response.data);
                        $('#pagination-container').html(response.pagination);
                        $('#kt_project_users_table_info').html(`Showing ${response.from} to ${response.to} of ${response.total} entries`);
                        // $('#advertiserTotal').val(response.total);
                    },
                    error: function(xhr) {
                        alert('Error occurred: ' + xhr.statusText);
                    },
                    complete: function() {
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
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                $('.pagination li').removeClass('active');
                $(this).parent('li').addClass('active');
                updateAndFetch({ page: new URL($(this).attr('href')).searchParams.get('page') });
            });

            $('#per-page-select, #statusFilter, #apply').on('change click', function() {
                passURLFields();
            });

            $('#search').on('keyup', function() {
                passURLFields();
            });

            $("#status").change(function() {
                passURLFields();
            });

            $("#applyAdvanceFilter").change(function() {
                passURLFields();
            });

            // Dismiss handler
            KTUtil.on(document.body,  '[data-kt-apply-advance-filter-dismiss="true"]', 'click', function(e) {
                var menu = KTMenu.getInstance(this);

                if ( menu !== null ) {
                    passURLFields();
                    return menu.dismiss(this, e);
                }
            });

            $("#exportBttn").click(function() {
                exportFieldSet();
            });
        });

        function copyLink(id)
        {
            navigator.clipboard.writeText(id)
        .then(() => {
            // Notify the user that the link was successfully copied
             alert("Deep Link Successfully Copied.");
           // normalMsg({"message": "Deep Link Successfully Copied.", "success": true});
        })
        .catch((err) => {
            // Notify the user about the error
            console.error("Failed to copy the link: ", err);
            alert("Failed to copy the link. Please try again.");
            //normalMsg({"message": "Failed to copy the link. Please try again.", "success": false});
        });

        }
    </script>
@endsection

@section('content')
            @include("partial.alert")
            <div class="card card-flush">
                <div class="card-header align-items-center pt-4">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative flex-grow-1 me-5" style="max-width: 300px;">
                            <i class="ri-search-line text-muted position-absolute ms-3"></i>
                            <input type="text"
                                id="search"
                                data-kt-ecommerce-order-filter="search"
                                class="form-control ps-5"
                                placeholder="Search by ID, name, etc..." />
                        </div>
                        <div id="kt_ecommerce_report_views_export" class="d-none">
                        </div>
                    </div>
                </div>
                <div class="card-body py-4">

                    <div id="transaction-container">
                        @include("publisher.payments.list_view", compact('payments'))
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
                                Showing {{$from}} to {{$to}}  of {{ $total }} entries
                            </div>
                        </div>

                        <div class="col-12 col-md-6 d-flex align-items-center justify-content-end" id="pagination-container">
                            {{ $payments->withQueryString()->links('partial.publisher_pagination') }}
                        </div>

                    </div>
                </div>
            </div>
<script>
    function copyLink(link){
        let text = link;
        let message = 'Link Copied Successfully!'
         var tempInput = document.createElement("textarea");
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

    // Display success message (optional)
    if (message) {
       normalMsg({"message": message, "success": true});
    }
    }
</script>
@endsection
