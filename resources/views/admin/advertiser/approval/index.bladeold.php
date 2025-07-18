@extends('layouts.admin.layout')

@section("styles")

    <style>
        table.dataTable tbody tr.selected {
            background-color: #ffe5b4 !important;
            color: #000;
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
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Advertisers</a></li>
                        <li class="breadcrumb-item"><a href="">{{ $title }}</a></li>
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
                <div class="card-header">
                    <h5 class="mt-1">{{ $title }} - Listing</h5>
                    {{-- <a href="javascript:void(0)" id="selectAll" class="btn btn-sm btn-primary my-0">Select All</a>--}}
                    {{-- <a href="javascript:void(0)" id="deSelectAll" class="btn btn-sm btn-danger my-0">De Select
                        All</a>--}}
                    {{-- <div class="float-end">--}}
                        {{-- <a href="javascript:void(0)" id="approve" class="btn btn-sm btn-success my-0">Approve</a>--}}
                        {{-- <a href="javascript:void(0)" id="hold" class="btn btn-sm btn-info my-0">Hold</a>--}}
                        {{-- <a href="javascript:void(0)" id="reject" class="btn btn-sm btn-danger my-0">Reject</a>--}}
                        {{-- </div>--}}
                </div>
                <div class="card-body">
                    <div class="dt-responsive table-responsive">
                        <table id="advertiserListing" class="table table table-condensed table-hover datatable">
                            <thead>
                                <tr>
                                    <th></th>
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
                                    <th></th>
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


    <script type="text/javascript">

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


    </script>

    <script type="text/javascript">

        $(document).ready(function () {
            let advertiserListingDraw = $('#advertiserListing').DataTable({
                processing: true,
                serverSide: true,
                pageLength: 50,
                select:
                {
                    style: 'multi'
                },
                dom: '<"d-flex justify-content-between mb-3"<\'length-wrapper\'l><\'filter-wrapper\'f>>t<"d-flex justify-content-between mt-2"<\'info-wrapper text-sm\'i><\'pagination-wrapper\'p>>',
                language: {
                    paginate: {
                        previous: '<i class="ri-arrow-left-s-line"></i>',
                        next: '<i class="ri-arrow-right-s-line"></i>'
                    }
                },
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

        });
    </script>

@endsection
