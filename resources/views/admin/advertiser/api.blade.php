@extends("layouts.admin.layout")

@section("styles")

    <!-- data tables css -->
    <link rel="stylesheet"
        href="{{ \App\Helper\Methods::staticAsset('panel/assets/plugins/data-tables/css/datatables.min.css') }}">

    <style>
        table td:last-child {
            padding: 0.65rem 0.75rem !important;
        }

        table td:last-child .btn {
            margin-right: 0 !important;
        }
    </style>

@endsection

@section('content')

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="ri-home-5-line text-primary"></i></a></li>
                        <li class="breadcrumb-item"><a href="">Advertisers</a></li>
                        <li class="breadcrumb-item"><a href="">API Advertisers</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- [ breadcrumb ] end -->

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Base style - Hover table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="advertiserListing" class="table table-hover nowrap">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Advert.
                                        ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">URL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Source
                                    </th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Is
                                        Tracking URL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Provider
                                        Status</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Advert. ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">URL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Source</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Is Tracking URL</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Provider Status</th>
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
    
    <script type="text/javascript">
        $(document).ready(function () {
            $('#advertiserListing').dataTable({
                scrollY: true,          // Enable vertical scrolling
                scrollX: true,          // Enable horizontal scrolling
                scrollCollapse: true,   // Allow scrolling collapse when content is smaller
                paging: true,           // Enable pagination
                autoWidth: false,       // Prevent automatic column width adjustment
                responsive: false,      // Disable responsive behavior if not needed
                ordering: true,         // Enable column ordering
                select:{
                    style:'multi'
                },
                pageLength: 50,
                dom: '<"d-flex justify-content-between mb-3"<\'length-wrapper\'l><\'filter-wrapper\'f>>t<"d-flex justify-content-between mt-2"<\'info-wrapper text-sm\'i><\'pagination-wrapper\'p>>',
                language: {
                    paginate: {
                        previous: '<i class="ri-arrow-left-s-line"></i>',
                        next: '<i class="ri-arrow-right-s-line"></i>'
                    }
                },
                columnDefs: [
                    { targets: '_all', className: 'equal-width align-middle text-center text-sm' }
                ],
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                ajax: {
                    url: '{{ route('admin.advertisers.api.ajax') }}?status={{ $title }}'
                },
                columns: [
                    {
                        data: 'advertiser_id',
                        name: 'advertiser_id',
                        render: function (data) {
                            return `<span class="text-sm fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'name',
                        name: 'name',
                        render: function (data) {
                            return `<span class="text-sm fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'url',
                        name: 'url',
                        render: function (data) {
                            return `<span class="text-sm fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'source',
                        name: 'source',
                        render: function (data) {
                            return `<span class="text-sm fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'is_tracking_url',
                        name: 'is_tracking_url',
                        render: function (data) {
                            return `<span class="text-sm fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'is_active',
                        name: 'is_active',
                        render: function (data) {
                            return `<span class="text-sm fw-bold">${data}</span>`;
                        }
                    },
                    {
                        data: 'action',
                        name: 'action',
                        render: function (data) {
                            return `<span class="text-sm fw-bold">${data}</span>`;
                        }
                    }
                ],
            });
        });
    </script>

@endsection
