@extends("layouts.admin.layout")

@section('styles')

    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.3.0/css/responsive.bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.bootstrap5.min.css">
    <style>
        #transactionListing .simplebar-scrollbar::before {
            background-color: #3498db !important;
            width: 10px !important;
        }

        #transactionListing .simplebar-track.simplebar-vertical {
            width: 12px !important;
        }

        #transactionListing .simplebar-track.simplebar-horizontal {
            height: 8px !important;
        }
    </style>
@endsection

@section('content')


    <!-- Page Header -->
    <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
        <nav>
            <ol class="breadcrumb mb-0 align-items-center">
                <li class="breadcrumb-item"><a href="javascript:void(0);" class="text-dark h4">Publisher</a></li>
                <li class="breadcrumb-item active text-muted fs-13 fw-normal" aria-current="page">Transactions</li>
            </ol>
        </nav>
    </div>
    <!-- Page Header Close -->

    @include('partial.admin.alert')

    <!-- Start::row-1 -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header">
                    <div class="card-title">
                        Transactions
                    </div>
                </div>
                <div class="card-body">
                    <div id="tableListingContent" class="simplebar" data-simplebar-auto-hide="false">
                        <table id="transactionListing" class="table table-bordered text-nowrap w-100">
                            <thead>
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
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--End::row-1 -->

@endsection

@section('scripts')

    <script src="https://code.jquery.com/jquery-3.6.1.min.js" integrity="sha256-o88AwQnZB+VDvE9tvIXrMQaPlFFSUTR+nldQm1LuPXQ=" crossorigin="anonymous"></script>

    <!-- Datatables Cdn -->
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.3.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.6/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

    <!-- Internal Datatables JS -->
    <script src="{{ \App\Helper\Methods::staticAsset('panel/assets/js/datatables.js') }}"></script>

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
                drawCallback: function(settings) {
                    // Re-initialize Simplebar on draw callback to ensure it applies to new content
                    new SimpleBar(document.getElementById('tableListingContent'), { autoHide: false });
                }
            });

        }, false);
    </script>

@endsection
