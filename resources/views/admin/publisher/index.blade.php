@extends("layouts.admin.layout")

@section('breadcrumb')
<ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
    <li class="breadcrumb-item mt-1">
        <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
    </li>
    <li class="breadcrumb-item mt-1">
        <a href="#" class="text-sm">Publishers</a>
    </li>
    <li class="breadcrumb-item mt-1 active">
        <a href="{{ route("admin.publishers.status", ['status' => $status]) }}" class="text-sm">{{ $title }}  Publishers</a>
    </li>
</ol>
@endsection

@section("styles")

    <!-- data tables css -->
<link rel="stylesheet" href="{{asset('assets/admin/plugins/css/dataTables.bootstrap4.min.css')}}">
<link rel="stylesheet" href="{{asset('assets/admin/plugins/css/select.bootstrap4.min.css')}}">


    <style>
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background-color: #ffffff !important;
            color: #000000 !important;
            /* optional: set text color for contrast */
            border: 1px solid #ccc !important;
            /* optional: adjust border if needed */
        }

        table.dataTable tbody tr.selected {
            background-color: #ffe5b4 !important;
            color: #000;
        }
        .length-wrapper label {
            display: flex;
            align-items: center;
            gap: 8px;
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
                    <a href="javascript:void(0)" id="selectAll" class="btn btn-sm btn-primary my-0">Select All</a>
                    <a href="javascript:void(0)" id="deSelectAll" class="btn btn-sm btn-danger my-0">De Select All</a>

                    <a href="javascript:void(0)" id="delete" class="btn btn-sm btn-danger my-0">Delete</a>

                </div>
                    <div class="card-body">
                        <div class="dt-responsive table-responsive">
                            <table id="publisherListing" class="table table-hover nowrap mb-0">
                                <thead>
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Created At</th>
                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Name</th>
                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Active Website</th>
                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Email</th>
                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Status</th>

                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Created At</th>
                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Name</th>
                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Active Website</th>
                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Email</th>
                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Status</th>

                                        <th
                                            class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                            Action</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
            </div>
            <!-- Base style - Hover table end -->
        </div>

@endsection

    @section("scripts")


        <script src="{{ asset('assets/admin/plugins/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{ asset('assets/admin/plugins/js/dataTables.bootstrap4.min.js')}}"></script>
        <script src="{{ asset('assets/admin/plugins/js/dataTables.select.min.js')}}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script type="text/javascript">
            $(document).ready(function () {
                let table = $('#publisherListing').DataTable({
                    scrollY: true,
                    scrollX: true,
                    scrollCollapse: true,
                    paging: true,
                    autoWidth: false,
                    responsive: false,
                    ordering: true,
                    pageLength: 50,
                    dom: '<"d-flex justify-content-between"<\'length-wrapper\'l><\'filter-wrapper\'f>>t<"d-flex justify-content-between mt-2"<\'info-wrapper text-sm\'i><\'pagination-wrapper\'p>>',
                    language: {
                        paginate: {
                            previous: '<i class="fas fa-chevron-left"></i>',
                            next: '<i class="fas fa-chevron-right"></i>'
                        }
                    },
                    lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                    select: {
                        style: 'multi'
                    },
                    ajax: {
                        url: '{{ route('admin.publishers.ajax') }}' + '?status={{ $title }}'
                    },
                    columns: [
                        {
                            data: 'created_at',
                            name: 'created_at',
                            render: function (data, type, row) {
                                return `
                                            <td class="text-center text-sm">
                                            <span class="text-sm fw-bold">${data}</span>
                                            </td>
                                        `;
                            }
                        },
                        {
                            data: 'name',
                            name: 'name',
                            render: function (data, type, row) {
                                return `
                                            <td class="text-center text-sm">
                                            <span class="text-sm fw-bold">${data}</span>
                                            </td>
                                        `;
                            }
                        },
                        {
                            data: 'active_website',
                            name: 'active_website',
                            render: function (data, type, row) {
                                return `
                                            <td class="text-center text-sm">
                                            <span class="text-sm fw-bold">${data}</span>
                                            </td>
                                        `;
                            }
                        },
                        {
                            data: 'email',
                            name: 'email',
                            render: function (data, type, row) {
                                return `
                                            <td class="text-center text-sm">
                                            <span class="text-sm fw-bold">${data}</span>
                                            </td>
                                        `;
                            }
                        },
                        {
                            data: 'status',
                            name: 'status',
                            render: function (data, type, row) {
                                return `
                                            <td class="text-center text-sm">
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
                                            <td class="text-center text-sm">
                                            <span class="text-sm fw-bold">${data}</span>
                                            </td>
                                        `;
                            }
                        }
                    ]
                });

                // Select All
                $('#selectAll').click(function () {
                    table.rows().select();
                });

                // Deselect All
                $('#deSelectAll').click(function () {
                    table.rows().deselect();
                });
            });
            function goToLogin(id) {
                Swal.fire({
                    title: 'Access Publisher Account',
                    text: "If you access publisher account. Then Admin account will be logout. Do you want to access?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Login'
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Please Wait!',
                            text: "Your publisher account will be access is few minutes.",
                            showConfirmButton: false,
                        });
                        window.location.href = `{{ url('/') }}/admin/publishers/access-login/${id}`;
                    }
                });
            }

            let selectAllButtonTrans = 'Select All'
            let selectNoneButtonTrans = 'De Select All'

            let buttons = [
                {
                    extend: 'selectAll',
                    className: 'btn btn-dark btn-sm mt-2 ml-2',
                    text: selectAllButtonTrans,
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'selectNone',
                    className: 'btn btn-danger btn-sm mt-2 ml-2',
                    text: selectNoneButtonTrans,
                    exportOptions: {
                        columns: ':visible'
                    }
                }
            ];

            function sendStatusData(ids, status) {
                $.ajax({
                    url: "{{ route('admin.advertisers.approval.status-update') }}",
                    type: 'POST',
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    data: { advertiser_idz: ids, status: status }
                }).done(function () { location.reload() });
            }

            function statusChange(status, approveButtonTrans, color) {
                let approveButton = {
                    text: approveButtonTrans,
                    className: `btn-${color} btn-sm mt-2 ml-2`,
                    action: function (e, dt, node, config) {
                        // console.log(`"${approveButtonTrans}" button clicked!`);

                        let ids = $.map(dt.rows({ selected: true }).data(), function (entry) {
                            // console.log("Row Data:", entry);  // Debug: Log full row data
                            return entry.id; // Ensure 'id' is correctly accessed
                        });

                        if (ids.length === 0) {
                            console.warn("No rows selected.");
                            Swal.fire("No Rows Selected", "Please select at least one publisher.", "warning");
                            return;
                        }

                        // console.log("Selected IDs:", ids);


                        Swal.fire({
                            title: `Are you sure you want to ${status} these publishers?`,
                            text: "This action cannot be undone!",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#d33",
                            cancelButtonColor: "#3085d6",
                            confirmButtonText: "Yes, Proceed"
                        }).then((result) => {
                            $.ajax({
                                url: "{{ route('admin.publishers.delete-all-selected') }}",
                                type: 'POST',
                                headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                                data: { ids: ids }
                            }).done(function () { location.reload() });
                        });
                    }
                };

                buttons.push(approveButton);
            }

            $('#delete').click(function () {
                // console.log("Delete button clicked!");  // Debugging log

                let table = $('#publisherListing').DataTable();
                let ids = $.map(table.rows({ selected: true }).data(), function (entry) {
                    // console.log("Row Data:", entry);  // Log row data
                    return entry.id;
                });

                if (ids.length === 0) {
                    console.warn("No rows selected.");
                    Swal.fire("No Rows Selected", "Please select at least one publisher.", "warning");
                    return;
                }

                // console.log("Selected IDs for deletion:", ids);

                Swal.fire({
                    title: "Are you sure?",
                    text: "You won't be able to revert this!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Yes, delete!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: "{{ route('admin.publishers.delete-all-selected') }}",
                            type: 'POST',
                            headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                            data: { ids: ids }
                        }).done(function () { location.reload() });
                    }
                });
            });

            @if($status == \App\Models\AdvertiserPublisher::STATUS_PENDING)
                statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_ACTIVE }}", "Approve", "success")
                statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_HOLD }}", "Hold", "info")
                statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_REJECTED }}", "Reject", "danger")
            @elseif($status == \App\Models\AdvertiserPublisher::STATUS_ACTIVE)
                statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_HOLD }}", "Hold", "info")
                statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_REJECTED }}", "Reject", "danger")
            @elseif($status == \App\Models\AdvertiserPublisher::STATUS_HOLD)
                statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_ACTIVE }}", "Approve", "success")
                statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_REJECTED }}", "Reject", "danger")
            @elseif($status == \App\Models\AdvertiserPublisher::STATUS_REJECTED)
                statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_ACTIVE }}", "Approve", "success")
                statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_HOLD }}", "Hold", "info")
            @endif

            $(function () {
                let languages = {
                    'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
                };
                $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
                $.extend(true, $.fn.dataTable.defaults, {
                    language: {
                        url: languages['{{ app()->getLocale() }}']
                    },
                    columnDefs: [{
                        orderable: false,
                        className: 'select-checkbox',
                        targets: 0
                    }, {
                        orderable: false,
                        searchable: false,
                        targets: -1
                    }],
                    select: {
                        style: 'multi+shift',
                        selector: 'td:first-child'
                    },
                    order: [],
                    dom: 'lBfrtip<"actions">',
                    buttons: buttons
                });
                $.fn.dataTable.ext.classes.sPageButton = '';
            });
        </script>



    @endsection
