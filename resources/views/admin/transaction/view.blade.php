@extends("layouts.admin.layout")

@section('styles')

<style>
    /* Base Styles */
    .transaction-dashboard {
        padding: 20px;
        background: #f8fafc;
    }

    .transaction-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .card-header {
        background: white;
        padding: 20px 25px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .card-title {
        font-weight: 600;
        color: #2d3748;
        margin: 0;
    }

    .transaction-id-badge .badge {
        background: #f37916;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Tab Styles */
    .nav-tabs {
        border-bottom: none;
        margin-top: 15px;
    }

    .nav-tabs .nav-item {
        margin-right: 5px;
    }

    .nav-tabs .nav-link {
        border: none;
        color: #718096;
        font-weight: 500;
        padding: 10px 20px;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s;
    }

    .nav-tabs .nav-link i {
        margin-right: 8px;
    }

    .nav-tabs .nav-link:hover {
        color: #f37916;
        background: rgba(243, 121, 22, 0.1);
    }

    .nav-tabs .nav-link.active {
        color: #f37916;
        background: white;
        border-bottom: 3px solid #f37916;
        font-weight: 600;
    }

    /* Summary Cards */
    .transaction-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .summary-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .summary-header {
        padding: 15px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f1f1;
    }

    .summary-header h6 {
        margin: 0;
        font-weight: 600;
        color: #4a5568;
        display: flex;
        align-items: center;
    }

    .summary-header i {
        margin-right: 10px;
        color: #f37916;
    }

    .summary-content {
        padding: 15px;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        border-bottom: 1px solid #f8f8f8;
    }

    .summary-item:last-child {
        border-bottom: none;
    }

    .summary-item span {
        color: #718096;
    }

    .summary-item strong {
        color: #2d3748;
        font-weight: 500;
        text-align: right;
    }

    .url-truncate {
        display: inline-block;
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        vertical-align: middle;
        color: #3182ce;
        text-decoration: none;
    }

    .url-truncate:hover {
        text-decoration: underline;
    }

    /* Details Sections */
    .transaction-details {
        background: white;
        border-radius: 10px;
        padding: 20px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        margin-bottom: 20px;
    }

    .section-title {
        color: #4a5568;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f1f1f1;
    }

    .timeline {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .timeline-item {
        background: #f8fafc;
        padding: 12px 15px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
    }

    .timeline-date {
        font-size: 12px;
        color: #718096;
        margin-bottom: 5px;
    }

    .timeline-value {
        font-weight: 500;
        color: #2d3748;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .detail-grid.cols-3 {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    }

    .detail-item {
        background: #f8fafc;
        padding: 12px 15px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
    }

    .detail-item span {
        font-size: 12px;
        color: #718096;
        margin-bottom: 5px;
    }

    .detail-item strong {
        font-weight: 500;
        color: #2d3748;
    }

    /* Parameters Grid */
    .parameters-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
    }

    .parameter-item {
        background: #f8fafc;
        padding: 12px 15px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
    }

    .parameter-item span {
        font-size: 12px;
        color: #718096;
        margin-bottom: 5px;
    }

    .parameter-item strong {
        font-weight: 500;
        color: #2d3748;
        word-break: break-word;
    }

    /* Financial Summary */
    .financial-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .financial-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .financial-header {
        padding: 15px;
        background: #f8fafc;
        border-bottom: 1px solid #f1f1f1;
    }

    .financial-header h6 {
        margin: 0;
        font-weight: 600;
        color: #4a5568;
        display: flex;
        align-items: center;
    }

    .financial-header i {
        margin-right: 10px;
        color: #f37916;
    }

    .financial-content {
        padding: 15px;
    }

    .financial-item {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f8f8f8;
    }

    .financial-item.highlight {
        background: rgba(243, 121, 22, 0.05);
        margin: -15px;
        padding: 15px;
        border-radius: 0;
    }

    .financial-item:last-child {
        border-bottom: none;
    }

    .financial-item span {
        color: #718096;
    }

    .financial-item strong {
        color: #2d3748;
        font-weight: 500;
        text-align: right;
    }

    /* Status Badges */
    .status-pending {
        color: #d69e2e;
    }

    .status-approved {
        color: #38a169;
    }

    .status-declined {
        color: #e53e3e;
    }

    /* Text Details */
    .text-details {
        display: grid;
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .text-card {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        padding: 20px;
    }

    .text-title {
        color: #4a5568;
        font-weight: 600;
        margin-top: 0;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .text-title i {
        margin-right: 10px;
        color: #f37916;
    }

    .text-content {
        color: #4a5568;
        line-height: 1.6;
    }

    .text-content p:last-child {
        margin-bottom: 0;
    }

    /* Responsive Adjustments */
    @media (max-width: 768px) {

        .transaction-summary,
        .financial-summary {
            grid-template-columns: 1fr;
        }

        .detail-grid,
        .parameters-grid {
            grid-template-columns: 1fr;
        }

        .nav-tabs .nav-link {
            padding: 8px 12px;
            font-size: 14px;
        }
    }
</style>

@endsection

@section('content')

    <!-- [ breadcrumb ] start -->
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-12">
                    <div class="page-header-title">
                        <h5>Transaction</h5>
                    </div>
                    <ul class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i
                                    class="ri-home-5-line text-primary"></i></a></li>
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Transaction Detail</a></li>
                        <li class="breadcrumb-item"><a href="{{ route("admin.transactions.index") }}">Transactions</a></li>
                        <li class="breadcrumb-item"><a href="">{{ $transaction->advertiser_name }}</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid transaction-dashboard">
        <!-- Alert Notification -->
        @include("partial.admin.alert")

        <div class="row">
            <div class="col-lg-12">
                <!-- Main Card -->
                <div class="card transaction-card">
                    <!-- Card Header with Tabs -->
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title">Transaction Details</h5>
                            <div class="transaction-id-badge">
                                <span class="badge">ID: {{ $transaction->transaction_id }}</span>
                            </div>
                        </div>

                        <!-- Modern Tabs -->
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="overview-tab" data-bs-toggle="tab" href="#overview">
                                    <i class="fas fa-info-circle"></i> Overview
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="commission-tab" data-bs-toggle="tab" href="#commission_rates">
                                    <i class="fas fa-money-bill-wave"></i> Commissions
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="terms-tab" data-bs-toggle="tab" href="#terms">
                                    <i class="fas fa-file-alt"></i> Text Details
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Card Body with Tab Content -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Overview Tab -->
                            <div class="tab-pane fade show active" id="overview" role="tabpanel">
                                <div class="transaction-summary">
                                    <div class="summary-card">
                                        <div class="summary-header">
                                            <h6><i class="fas fa-user-tie"></i> Advertiser</h6>
                                        </div>
                                        <div class="summary-content">
                                            <div class="summary-item">
                                                <span>Name</span>
                                                <strong>{{ $transaction->advertiser_name }}</strong>
                                            </div>
                                            <div class="summary-item">
                                                <span>Country</span>
                                                <strong>{{ $transaction->advertiser_country ?? "-" }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="summary-card">
                                        <div class="summary-header">
                                            <h6><i class="fas fa-bullhorn"></i> Campaign</h6>
                                        </div>
                                        <div class="summary-content">
                                            <div class="summary-item">
                                                <span>Name</span>
                                                <strong>{{ $transaction->campaign_name ?? "-" }}</strong>
                                            </div>
                                            <div class="summary-item">
                                                <span>Site</span>
                                                <strong>{{ $transaction->site_name ?? "-" }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="summary-card">
                                        <div class="summary-header">
                                            <h6><i class="fas fa-link"></i> URLs</h6>
                                        </div>
                                        <div class="summary-content">
                                            <div class="summary-item">
                                                <span>Main URL</span>
                                                <a href="{{ $transaction->url ?? '#' }}" target="_blank"
                                                    class="url-truncate">{{ $transaction->url ?? "-" }}</a>
                                            </div>
                                            <div class="summary-item">
                                                <span>Publisher URL</span>
                                                <a href="{{ $transaction->publisher_url ?? '#' }}" target="_blank"
                                                    class="url-truncate">{{ $transaction->publisher_url ?? "-" }}</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Detailed Table -->
                                <div class="transaction-details">
                                    <div class="details-section">
                                        <h6 class="section-title">Transaction Timeline</h6>
                                        <div class="timeline">
                                            <div class="timeline-item">
                                                <span class="timeline-date">Click Date</span>
                                                <span class="timeline-value">{{ $transaction->click_date ?? "-" }}</span>
                                            </div>
                                            <div class="timeline-item">
                                                <span class="timeline-date">Transaction Date</span>
                                                <span
                                                    class="timeline-value">{{ $transaction->transaction_date ?? "-" }}</span>
                                            </div>
                                            <div class="timeline-item">
                                                <span class="timeline-date">Validation Date</span>
                                                <span
                                                    class="timeline-value">{{ $transaction->validation_date ?? "-" }}</span>
                                            </div>
                                            <div class="timeline-item">
                                                <span class="timeline-date">Lapse Time</span>
                                                <span class="timeline-value">{{ $transaction->lapse_time ?? "-" }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="details-section">
                                        <h6 class="section-title">Technical Details</h6>
                                        <div class="detail-grid">
                                            <div class="detail-item">
                                                <span>Click Device</span>
                                                <strong>{{ $transaction->click_device ?? "-" }}</strong>
                                            </div>
                                            <div class="detail-item">
                                                <span>Customer Country</span>
                                                @if (!empty($transaction->customer_country))
                                                    <img src="https://flagsapi.com/{{ $transaction->customer_country }}/flat/64.png" title="{{ $transaction->customer_country }}" height="24" width="40" class="img-fluid cursor-pointer"  alt="{{ $transaction->customer_country }}">
                                                @else
                                                <strong>{{ $transaction->customer_country ?? "-" }}</strong>
                                                @endif
                                            </div>
                                            <div class="detail-item">
                                                <span>IP Hash</span>
                                                <strong>{{ $transaction->ip_hash ?? "-" }}</strong>
                                            </div>
                                            <div class="detail-item">
                                                <span>Source</span>
                                                <strong>{{ $transaction->source ?? "-" }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Custom Parameters -->
                                    @if($transaction->custom_parameters)
                                        <div class="details-section">
                                            <h6 class="section-title">Custom Parameters</h6>
                                            <div class="parameters-grid">
                                                @foreach($transaction->custom_parameters as $param)
                                                    <div class="parameter-item">
                                                        <span>{{ $param['key'] ?? 'Parameter' }}</span>
                                                        <strong>{{ $param['value'] ?? '-' }}</strong>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Commission Tab -->
                            <div class="tab-pane fade" id="commission_rates" role="tabpanel">
                                <div class="financial-summary">
                                    <div class="financial-card">
                                        <div class="financial-header">
                                            <h6><i class="fas fa-chart-line"></i> Commission Summary</h6>
                                        </div>
                                        <div class="financial-content">
                                            <div class="financial-item highlight">
                                                <span>Status</span>
                                                <strong class="status-{{ strtolower($transaction->commission_status) }}">
                                                    {{ ucwords($transaction->commission_status) }}
                                                </strong>
                                            </div>
                                            <div class="financial-item">
                                                <span>Type</span>
                                                <strong>{{ $transaction->commission_type ?? "-" }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="financial-card">
                                        <div class="financial-header">
                                            <h6><i class="fas fa-money-bill-alt"></i> Received Amounts</h6>
                                        </div>
                                        <div class="financial-content">
                                            <div class="financial-item">
                                                <span>Sale Amount</span>
                                                <strong>{{ $transaction->received_sale_amount ?? "-" }}
                                                    {{ $transaction->sale_amount_currency ?? "" }}</strong>
                                            </div>
                                            <div class="financial-item">
                                                <span>Commission</span>
                                                <strong>{{ $transaction->received_commission_amount ?? "-" }}
                                                    {{ $transaction->received_commission_amount_currency ?? "" }}</strong>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="financial-card">
                                        <div class="financial-header">
                                            <h6><i class="fas fa-calculator"></i> Calculated Amounts</h6>
                                        </div>
                                        <div class="financial-content">
                                            <div class="financial-item">
                                                <span>Sale Amount</span>
                                                <strong>{{ $transaction->sale_amount ?? "-" }}
                                                    {{ $transaction->sale_amount_currency ?? "" }}</strong>
                                            </div>
                                            <div class="financial-item">
                                                <span>Commission</span>
                                                <strong>{{ $transaction->commission_amount ?? "-" }}
                                                    {{ $transaction->commission_amount_currency ?? "" }}</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Additional Financial Details -->
                                <div class="financial-details">
                                    <h6 class="section-title">Additional Financial Information</h6>
                                    <div class="detail-grid cols-3">
                                        <div class="detail-item">
                                            <span>Tracked Amount</span>
                                            <strong>{{ $transaction->tracked_currency_amount ?? "-" }}
                                                {{ $transaction->tracked_currency_currency ?? "" }}</strong>
                                        </div>
                                        <div class="detail-item">
                                            <span>Original Sale</span>
                                            <strong>{{ $transaction->original_sale_amount ?? "-" }}</strong>
                                        </div>
                                        <div class="detail-item">
                                            <span>Paid to Publisher</span>
                                            <strong>{{ $transaction->paid_to_publisher ?? "-" }}</strong>
                                        </div>
                                        <div class="detail-item">
                                            <span>Old Sale Amount</span>
                                            <strong>{{ $transaction->old_sale_amount ?? "-" }}</strong>
                                        </div>
                                        <div class="detail-item">
                                            <span>Old Commission</span>
                                            <strong>{{ $transaction->old_commission_amount ?? "-" }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Terms Tab -->
                            <div class="tab-pane fade" id="terms" role="tabpanel">
                                <div class="text-details">
                                    <div class="text-card">
                                        <h6 class="text-title"><i class="fas fa-edit"></i> Amended Reason</h6>
                                        <div class="text-content">
                                            {!! $transaction->amended_reason ?? "<span class='text-muted'>Not available</span>" !!}
                                        </div>
                                    </div>

                                    <div class="text-card">
                                        <h6 class="text-title"><i class="fas fa-times-circle"></i> Decline Reason</h6>
                                        <div class="text-content">
                                            {!! $transaction->decline_reason ?? "<span class='text-muted'>Not available</span>" !!}
                                        </div>
                                    </div>

                                    <div class="text-card">
                                        <h6 class="text-title"><i class="fas fa-users"></i> Customer Acquisition</h6>
                                        <div class="text-content">
                                            {!! $transaction->customer_acquisition ?? "<span class='text-muted'>Not available</span>" !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section("scripts")
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ \App\Helper\Methods::staticAsset('panel/assets/plugins/data-tables/js/datatables.min.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {

            $('#transactionListing').dataTable({
                scrollY: true,          // Enable vertical scrolling
                scrollX: true,          // Enable horizontal scrolling
                scrollCollapse: true,   // Allow scrolling collapse when content is smaller
                paging: true,           // Enable pagination
                autoWidth: false,       // Prevent automatic column width adjustment
                responsive: false,      // Disable responsive behavior if not needed
                ordering: true,         // Enable column ordering
                pageLength: 250,
                lengthMenu: [10, 25, 50, 100, 250, 500, 1000],
                ajax: {
                    url: "{{ route('admin.transactions.ajax') }}",
                    data: function (d) {
                        { { --d.source = $('#source').val(); --} }
                        { { --d.country = $('#country').val(); --} }
                        { { --d.search_filter = $('#search_filter').val(); --} }
                        { { --d.payment_id = "{{ request()->input('payment_id') ?? '' }}"; --} }
                        { { --d.r_name = "{{ request()->input('r_name') ?? '' }}"; --} }
                    }
                },
                columns: [
                    { data: 'transaction_id', name: 'transaction_id' },
                    { data: 'advertiser_name', name: 'advertiser_name' },
                    { data: 'transaction_date', name: 'transaction_date' },
                    { data: 'customer_country', name: 'customer_country' },
                    { data: 'advertiser_country', name: 'advertiser_country' },
                    { data: 'commission_status', name: 'commission_status' },
                    { data: 'payment_status', name: 'payment_status' },
                    { data: 'commission_amount', name: 'commission_amount' },
                    { data: 'commission_amount_currency', name: 'commission_amount_currency' },
                    { data: 'sale_amount', name: 'sale_amount' },
                    { data: 'received_commission_amount', name: 'received_commission_amount' },
                    { data: 'received_sale_amount', name: 'received_sale_amount' },
                    { data: 'sale_amount_currency', name: 'sale_amount_currency' },
                    { data: 'received_commission_amount_currency', name: 'received_commission_amount_currency' },
                    { data: 'source', name: 'source' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
            });

        }, false);
    </script>
    <script>
        $('#overview_tab').click(function () {
            $('.tab-pane').removeClass('active show');
            $('.btn[data-toggle="tab"]').removeClass('active');

            // Add 'active' and 'show' classes to the #overview tab content
            $('#overview').addClass('active show');

            // Optionally, set the tab button to active (if required)
            $(this).addClass('active');
        })

        $('#commission_rates-tab').click(function () {
            $('.tab-pane').removeClass('active show');
            $('.btn[data-toggle="tab"]').removeClass('active');

            // Add 'active' and 'show' classes to the #overview tab content
            $('#commission_rates').addClass('active show');

            // Optionally, set the tab button to active (if required)
            $(this).addClass('active');
        })

        $('#terms-tab').click(function () {
            $('.tab-pane').removeClass('active show');
            $('.btn[data-toggle="tab"]').removeClass('active');

            // Add 'active' and 'show' classes to the #overview tab content
            $('#terms').addClass('active show');

            // Optionally, set the tab button to active (if required)
            $(this).addClass('active');
        })
    </script>
@endsection
