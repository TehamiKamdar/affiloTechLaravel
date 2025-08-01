@extends("layouts.admin.layout")

@section("styles")

    <!-- data tables css -->
        <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/admin/plugins/datatables/css/select.bootstrap4.min.css')}}">
    <style>
        table td:last-child {
            padding: 0.65rem 0.75rem !important;
        }

        table td:last-child .btn {
            margin-right: 0 !important;
        }

        .dataTables_length {
            float: left;
            margin-right: 25px;
        }

        .ml-2 {
            margin-left: .5rem !important;
        }
        .dataTables_processing {
            display: none;
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

@section('breadcrumb')
<ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Creative</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">Coupons</a>
        </li>
    </ol>
@endsection

@section('content')

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Base style - Hover table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5>Creatives - Listing</h5>
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="transactionListing" class="table table table-hover datatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Advertiser Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Coupon Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Start Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">End Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Source</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Advertiser Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Coupon Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Start Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">End Date</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Source</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action</th>
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

    <script src="{{ asset('assets/admin/plugins/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/admin/plugins/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
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
                pageLength: 250,
                language: {
                    paginate: {
                        previous: '<i class="fas fa-chevron-left"></i>',
                        next: '<i class="fas fa-chevron-right"></i>'
                    }
                },
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                ajax: {
                    url: "{{ route('admin.creatives.creativeajax') }}",
                    data: function (d) {
                        {{--d.source = $('#source').val();--}}
                        {{--d.country = $('#country').val();--}}
                        {{--d.search_filter = $('#search_filter').val();--}}
                        {{--d.payment_id = "{{ request()->input('payment_id') ?? '' }}";--}}
                        {{--d.r_name = "{{ request()->input('r_name') ?? '' }}";--}}
                    },
            dataSrc: function (json) {
                console.log('AJAX Response:', json); // Debug the AJAX response
                return json.data;
            }
                },
                columns: [
                    {data: 'advertiser_name', name: 'advertiser_name'},
                    {data: 'title', name: 'title'},
                    {data: 'start_date', name: 'start_date'},
                    {data: 'end_date', name: 'end_date'},
                    {data: 'source', name: 'source'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],

            });

        }, false);
    </script>

@endsection
