@extends("layouts.admin.layout")

@section('styles')
    <style>
        .width-150 {
            width: 150%;
            overflow-x: scroll;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">

        $('#transactionListing').dataTable({
            scrollY:        true,
            scrollX:        true,
            scrollCollapse: true,
            paging:         true,
            autoWidth:      false,
            deferRender:    false,
            responsive:     false,
            ordering :      true,
            sScrollXInner:  "199.5%",
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
            ajax: {
                url: "{{ route('admin.transactions.ajax') }}",
                data: function (d) {
                    d.source = $('#source').val();
                    d.country = $('#country').val();
                    d.search_filter = $('#search_filter').val();
                    d.payment_id = "{{ request()->input('payment_id') ?? '' }}";
                    d.r_name = "{{ request()->input('r_name') ?? '' }}";
                }
            },
            columns: [
                {data: 'transaction_id', name: 'transaction_id'},
                {data: 'advertiser_name', name: 'advertiser_name'},
                {data: 'transaction_date', name: 'transaction_date'},
                {data: 'customer_country', name: 'customer_country'},
                {data: 'advertiser_country', name: 'advertiser_country'},
                {data: 'commission_status', name: 'commission_status'},
                {data: 'payment_status', name: 'payment_status'},
                {data: 'commission_amount', name: 'commission_amount'},
                {data: 'commission_amount_currency', name: 'commission_amount_currency'},
                {data: 'sale_amount', name: 'sale_amount'},
                {data: 'received_commission_amount', name: 'received_commission_amount'},
                {data: 'received_sale_amount', name: 'received_sale_amount'},
                {data: 'sale_amount_currency', name: 'sale_amount_currency'},
                {data: 'received_commission_amount_currency', name: 'received_commission_amount_currency'},
                {data: 'source', name: 'source'},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ],
            initComplete: function() {
                $(this).addClass('table table-row-bordered table-row-dashed gy-4 align-middle fw-bold width-150');
                $(this).dataTable().fnAdjustColumnSizing();
            }
        });

    </script>
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title flex-column">
                            <h3 class="fw-bold mb-1">Transactions</h3>
                        </div>
                        <div class="card-toolbar my-1">
                            <!-- You can add select filters or other controls here if needed -->
                        </div>
                    </div>
                    <div class="card-body pt-0">

                        @include('partial.admin.alert')

                        <div class="table-responsive">
                            <table id="transactionListing" class="table table-row-bordered table-row-dashed gy-4 align-middle fw-bold width-150">
                                <thead class="fs-7 text-uppercase">
                                <tr>
                                    <th>
                                        Transaction ID
                                    </th>
                                    <th>
                                        Advertiser Name
                                    </th>
                                    <th>
                                        Transaction Date
                                    </th>
                                    <th>
                                        Country
                                    </th>
                                    <th>
                                        Advertiser Country
                                    </th>
                                    <th>
                                        Commission Status
                                    </th>
                                    <th>
                                        Payment Status
                                    </th>
                                    <th>
                                        Commission Amount
                                    </th>
                                    <th>
                                        Received Commission Amount
                                    </th>
                                    <th>
                                        Commission Amount Currency
                                    </th>
                                    <th>
                                        Sale Amount
                                    </th>
                                    <th>
                                        Received Sale Amount
                                    </th>
                                    <th>
                                        Sale Amount Currency
                                    </th>
                                    <th>
                                        Received Sale Amount Currency
                                    </th>
                                    <th>
                                        Source
                                    </th>
                                    <th class="text-end">Action</th>
                                </tr>
                                </thead>
                                <tbody class="fw-semibold text-gray-600"></tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
