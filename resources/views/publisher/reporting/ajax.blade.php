<!--begin::Loader-->
<div id="table-loader" class="table-loader" style="display: none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<!--end::Loader-->

<div class="table-responsive">
    <!--begin::Table-->
<table class="table table-hover fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-uppercase">
            <th>Advertiser</th>
            <th>Date</th>
            <th>Sales Amount</th>
            <th>Comm. Amount</th>
        </tr>
    </thead>
    <tbody class="text-gray-600 fw-semibold">
        @if(count($transactions))

            @foreach($transactions as $transaction)
                @php
                    $advertiser = \App\Models\Advertiser::find($transaction->internal_advertiser_id);
                    $initials = \App\Helper\Methods::getInitials($transaction->advertiser_name);
                    $colorCode = \App\Helper\Methods::getColorFromName($transaction->advertiser_name);
                    $imageUrl = "https://placehold.co/32/{$colorCode}/FFFFFF?text={$initials}";
                   @endphp
                <tr>
                    <td>
                        <a href="{{ route("publisher.view-advertiser", ['advertiser' => $transaction->internal_advertiser_id]) }}"class="nav-link px-0 d-flex align-items-center" style="gap: 8px;">
                            <img src="{{ $imageUrl }}" alt="{{ $initials }}" class="rounded-circle" width="32" height="32">
                            <div>
                                <h6 class="fw-semibold mb-0">{{ \Illuminate\Support\Str::limit($transaction->advertiser_name, 50, '....') }}</h6>
                                <span class="text-muted">({{ $advertiser->sid }})</span>
                            </div>
                        </a>
                    </td>
                    <td>{{ $transaction->transaction_date }}</td>
                    <td>${{ number_format($transaction->sale_amount ?? 0, 2) }}</td>
                    <td>${{ number_format($transaction->commission_amount ?? 0, 2) }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="6" class="text-center">
                    <small>No Transactions Record</small>
                </td>
            </tr>
        @endif
    </tbody>
</table>
</div>
