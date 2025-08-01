@extends("layouts.admin.layout")

@section('breadcrumb')
<ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Transactions Management</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">Transactions</a>
        </li>
    </ol>
@endsection

@section("styles")

    <!-- data tables css -->
    <link rel="stylesheet" href="{{ asset('adminDashboard/assets/plugins/data-tables/css/datatables.min.css') }}">

    <style>
        table td:last-child{
            padding: 0.65rem 0.75rem !important;
        }

        table td:last-child .btn {
            margin-right: 0 !important;
        }
        select option {
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
            border: 2px solid #e0e3ed;
            color: var(--primary-color);
            font-weight: 500;
            border-radius: 8px !important;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary-color);
        }
    </style>

@endsection

@section('content')

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Base style - Hover table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Transactions - Listing</h5>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="transactionListing"
                               class="table table table-hover datatable nowrap">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Transaction ID</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Advertiser Name</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Transaction Date</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Country</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Advertiser Country</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Commission Status</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Payment Status</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Commission Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Received Commission Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Commission Amount Currency</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Sale Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Received Sale Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Sale Amount Currency</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Received Sale Amount Currency</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Source</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Transaction ID</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Advertiser Name</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Transaction Date</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Country</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Advertiser Country</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Commission Status</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Payment Status</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Commission Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Received Commission Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Commission Amount Currency</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Sale Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Received Sale Amount</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Sale Amount Currency</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Received Sale Amount Currency</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Source</th>
                                    <th class="text-center text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Base style - Hover table end -->
    </div>

@endsection

@section("scripts")

    <script src="{{ asset('assets/admin/plugins/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/admin/plugins/js/dataTables.bootstrap4.min.js')}}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {

            $('#transactionListing').dataTable({
                scrollY: true,          // Enable vertical scrolling
                scrollX: true,          // Enable horizontal scrolling
                scrollCollapse: true,   // Allow scrolling collapse when content is smaller
                paging: true,           // Enable pagination
                autoWidth: false,       // Prevent automatic column width adjustment
                responsive: false,      // Disable responsive behavior if not needed
                ordering: true,         // Enable column ordering
                pageLength: 50,
                dom: '<"d-flex justify-content-between mb-3"<\'length-wrapper\'l><\'filter-wrapper\'f>>t<"d-flex justify-content-between mt-2"<\'info-wrapper text-sm\'i><\'pagination-wrapper\'p>>',
                language: {
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                ajax: {
                    url: "{{ route('admin.transactions.ajax') }}",
                    data: function (d) {
                        {{--d.source = $('#source').val();--}}
                        {{--d.country = $('#country').val();--}}
                        {{--d.search_filter = $('#search_filter').val();--}}
                        {{--d.payment_id = "{{ request()->input('payment_id') ?? '' }}";--}}
                        {{--d.r_name = "{{ request()->input('r_name') ?? '' }}";--}}
                    }
                },
                columns: [
                    {
                        data: 'transaction_id',
                        name: 'transaction_id',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'advertiser_name',
                        name: 'advertiser_name',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'transaction_date',
                        name: 'transaction_date',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'customer_country',
                        name: 'customer_country',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <img src="https://flagsapi.com/${data}/flat/32.png" title="${data}" class="img-fluid cursor-pointer" alt="${data}">
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'advertiser_country',
                        name: 'advertiser_country',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'commission_status',
                        name: 'commission_status',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'payment_status',
                        name: 'payment_status',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'commission_amount',
                        name: 'commission_amount',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'received_commission_amount',
                        name: 'received_commission_amount',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'commission_amount_currency',
                        name: 'commission_amount_currency',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'sale_amount',
                        name: 'sale_amount',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'received_sale_amount',
                        name: 'received_sale_amount',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'sale_amount_currency',
                        name: 'sale_amount_currency',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'received_commission_amount_currency',
                        name: 'received_commission_amount_currency',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {
                        data: 'source',
                        name: 'source',
                        render: function (data, type, row) {
                            return `
                                <td class="equal-width align-middle text-center text-sm">
                                <span class="text-sm fw-bold">${data}</span>
                                </td>
                            `;
                        }
                    },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
            });

        }, false);
    </script>

@endsection
