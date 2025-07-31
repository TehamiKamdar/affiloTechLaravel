@extends("layouts.admin.layout")

@section('breadcrumb')
    <ol class="breadcrumb mb-0 bg-white rounded-50 nav-link nav-link-lg collapse-btn">
        <li class="breadcrumb-item mt-1">
            <a href="{{ route('publisher.dashboard') }}"><i data-feather="home"></i></a>
        </li>
        <li class="breadcrumb-item mt-1">
            <a href="#" class="text-sm">Advertisers</a>
        </li>
        <li class="breadcrumb-item mt-1 active">
            <a href="#" class="text-sm">{{ $title }}</a>
        </li>
    </ol>
@endsection

@section("styles")

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

    </style>

@endsection

@section('content')

    <!-- [ Main Content ] start -->
    <div class="row">
        <!-- Base style - Hover table start -->
        <div class="col-sm-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mt-1">{{ $title }} - Listing</h5>

                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="advertiserListing" class="table table table-hover datatable">
                            <thead>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created
                                        At</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Network
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Advert.
                                        ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Advert.
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Publ.
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Publ.
                                        Website</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Applied
                                        At</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7"></th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Created
                                        At</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Network
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Advert.
                                        ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Advert.
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Publ.
                                        Name</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Publ.
                                        Website</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Applied
                                        At</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Action
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


    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>\
    <script src="{{ asset('assets/admin/plugins/datatables/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/admin/plugins/datatables/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('assets/admin/plugins/datatables/js/dataTables.select.min.js')}}"></script>

    <script>

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
            }).done(function () { location.reload(); });
        }

        function statusChange(status, approveButtonTrans, color) {
            let approveButton = {
                text: approveButtonTrans,
                className: `btn-${color} btn-sm mt-2 ml-2`,
                action: function (e, dt, node, config) {
                    let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                        return $(entry).attr("id");
                    });
                    if (ids.length === 0) {
                        alert('No rows selected')
                        return
                    }
                    if (confirm('Are you sure?')) {
                        // console.log(ids)
                        sendStatusData(ids, status)
                    }
                }
            }
            buttons.push(approveButton)
        }

        @if($apiTitle == \App\Models\AdvertiserPublisher::STATUS_PENDING)
            statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_ACTIVE }}", "Approve", "success")
            statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_HOLD }}", "Hold", "info")
            statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_REJECTED }}", "Reject", "danger")
        @elseif($apiTitle == \App\Models\AdvertiserPublisher::STATUS_ACTIVE)
            statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_HOLD }}", "Hold", "info")
            statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_REJECTED }}", "Reject", "danger")
        @elseif($apiTitle == \App\Models\AdvertiserPublisher::STATUS_HOLD)
            statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_ACTIVE }}", "Approve", "success")
            statusChange("{{ \App\Models\AdvertiserPublisher::STATUS_REJECTED }}", "Reject", "danger")
        @elseif($apiTitle == \App\Models\AdvertiserPublisher::STATUS_REJECTED)
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

    <script type="text/javascript">
        let advertiserListingDraw;
        $(document).ready(function () {
            advertiserListingDraw = $('#advertiserListing').DataTable({
                processing: true,
                serverSide: true,
                deferRender: true,
                sScrollXInner: "100%",
                scrollY: true,
                scrollX: true,
                scrollCollapse: true,
                paging: true,
                ordering: true,
                pageLength: 250,
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                ajax: {
                    url: '{{ route('admin.advertisers.approval.ajax') }}?status={{ $apiTitle }}'
                },
                columns: [
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                    {
                        data: 'created_at',
                        name: 'created_at',
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
                    {
                        data: 'advertiser_sid',
                        name: 'advertiser_sid',
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
                        data: 'publisher_name',
                        name: 'publisher_name',
                        render: function (data, type, row) {
                            return `
                                            <td class="equal-width align-middle text-center text-sm">
                                            <span class="text-sm fw-bold">${data}</span>
                                            </td>
                                        `;
                        }
                    },
                    {
                        data: 'publisher_website',
                        name: 'publisher_website',
                        render: function (data, type, row) {
                            return `
                                            <td class="equal-width align-middle text-center text-sm">
                                            <span class="text-sm fw-bold">${data}</span>
                                            </td>
                                        `;
                        }
                    },
                    {
                        data: 'applied_at',
                        name: 'applied_at',
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
                    }
                ]
            });
            let networkList = @json($list);

            setTimeout(function () {
                let selectHtml = `
                <label style="margin-right: 10px; display: flex; gap: 3px; align-items: center;">
                    <span>Select Network:</span>
                    <select class="form-control form-control-sm" id="networkFilter" name="source" aria-controls="advertiserListing">
                        <option value="All" selected>All</option>`;

                for (let i = 0; i < networkList.length; i++) {
                    selectHtml += `<option value="${networkList[i]}">${networkList[i]}</option>`;
                }

                selectHtml += `</select></label>`;

                $('#advertiserListing_filter').append(selectHtml);

                $('#networkFilter').on('change', function () {
                    var selected = $(this).val();
                    // Build a new URL that includes the selected network as a parameter
                    var newUrl = '{{ route("admin.advertisers.approval.ajax") }}?status={{ $apiTitle }}&source=' + encodeURIComponent(selected);
                    advertiserListingDraw.ajax.url(newUrl).load();
                });
            }, 2000);
        });
    </script>

@endsection
