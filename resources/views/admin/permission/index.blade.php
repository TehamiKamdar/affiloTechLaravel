@extends("layouts.admin.layout")

@section('breadcrumb')
<ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Manage</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">Permissions</a>
        </li>
    </ol>
@endsection

@section("styles")

    <!-- data tables css -->
    <link rel="stylesheet"
        href="{{ asset('adminDashboard/assets/plugins/data-tables/css/datatables.min.css') }}">

    <style>
        table td:last-child {
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
                <div class="card-header justify-content-between">
                    <h5>Permissions - Listing</h5>
                    <div class="d-flex justify-content-end">
                        <a href="{{route('admin.permissions.create')}}" class="btn btn-primary btn-sm">Create New Permission</a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="transactionListing" class="table table table-hover datatable nowrap">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">Action
                                    </th>
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
        document.addEventListener('DOMContentLoaded', function () {

            $('#transactionListing').DataTable({
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
                        previous: '<i class="ri-arrow-left-s-line"></i>',
                        next: '<i class="ri-arrow-right-s-line"></i>'
                    }
                },
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                ajax: {
                    url: "{{ route('admin.permissions.permissionajax') }}",
                    data: function (d) {
                        // { { --d.source = $('#source').val(); --} }
                        // { { --d.country = $('#country').val(); --} }
                        // { { --d.search_filter = $('#search_filter').val(); --} }
                        // { { --d.payment_id = "{{ request()->input('payment_id') ?? '' }}"; --} }
                        // { { --d.r_name = "{{ request()->input('r_name') ?? '' }}"; --} }
                    },
                    dataSrc: function (json) {
                        // console.log('AJAX Response:', json); // Debug the AJAX response
                        return json.data;
                    }
                },
                columns: [
                    {
                        data: 'name',
                        name: 'name',
                        render: function (data, type, row) {
                            return `
                                    <td class="equal-width align-middle text-center text-sm">
                                    <span class="text-sm fw-bold">${data}</span>
                                    </td>
                                `;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render: function (data, type, row) {
                            return `
                                    <td class="equal-width align-middle text-center text-sm">
                                    <span class="text-sm fw-bold">${data}</span>
                                    </td>
                                `;
                        }
                    },
                ],

            });

        }, false);
    </script>

@endsection
