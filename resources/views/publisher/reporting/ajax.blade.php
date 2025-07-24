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
        <tr>
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
                   @endphp
                <tr>
                    <td>
                        <!--begin::User details-->
                        <div class="d-flex flex-column">
                            <a href="{{ route("publisher.view-advertiser", ['advertiser' => $transaction->internal_advertiser_id]) }}"
                                class="text-gray-800 text-hover-primary mb-1">
                                {{ \Illuminate\Support\Str::limit($transaction->advertiser_name, 50, '....') }}
                            </a>

                            <span class="text-sm">({{ $advertiser->sid }})</span>
                        </div>
                        <!--end::User details-->
                    </td>
                    <td>
                        <div>
                            <h6 class="fw-semibold text-dark mt-2">{{ $transaction->transaction_date }}</h6>
                        </div>
                    </td>

                    <td class="pe-0">
                        <div>
                            <h6 class="fw-semibold text-dark mt-2">${{ number_format($transaction->sale_amount ?? 0, 2) }}</h6>
                        </div>
                    </td>
                    <td class="pe-0">
                        <div>
                            <h6 class="fw-semibold text-dark mt-2">${{ number_format($transaction->commission_amount ?? 0, 2) }}</h6>
                        </div>
                    </td>


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
