<!--begin::Loader-->
<div id="table-loader" class="table-loader" style="display: none;">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>
<!--end::Loader-->

<!--begin::Table-->
<table class="table table-striped align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
    <thead>
        <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-150px">Invoice#</th>
            <th class="min-w-100px">Date</th>
 <th class="text-end min-w-100px">Payment ID</th>
            <th class="text-end min-w-100px">Method</th>
            <th class="text-end min-w-70px">Amount</th>
               <th class="text-end min-w-100px">LC Revshare</th>
                <th class="text-end min-w-100px">Paid Amount</th>
                 <th class="text-end min-w-100px">Paid Date</th>
                 <th class="text-end min-w-100px">Status</th>
            
        </tr>
    </thead>
    <tbody class="table table-striped text-gray-600 fw-semibold">
        @if(count($payments))
            @foreach($payments as $key => $payment)
                <tr>
                    <td class="d-flex align-items-center">
                       
                        
                        <div class="d-flex flex-column">
                            <a class="text-gray-800 text-hover-primary mb-1">
                                {{ $payment->invoice_id }}
                            </a>
                           
                        </div>
                      
                    </td>
                    <td>
                        <a class="fw-bold"> {{ \Carbon\Carbon::parse($payment->created_at)->format("d-m-Y") }}</a>
                    </td>
                    
                    <td class="text-end pe-0">
                        <a>{{ $payment->publisher_id}}</a>
                    </td>
                   
                    <td class="text-end pe-0">
                        <a>{{ ucwords($payment->payment_method->payment_method ?? "-") }}</a>
                    </td>
                   <td class="text-end pe-0">
    <a>${{ number_format($payment->commission_amount, 2) ?? 0 }}</a>
    </td>
     <td>
                                <div class="orderDatatable-title">
                                    @php
                                        if($payment->is_new_invoice == \App\Models\PaymentHistory::INVOICE_NEW)
                                        {
                                            $cappedAmount = 30;
                                            if($payment->payment_method->payment_method == \App\Helper\Static\Vars::PAYONEER) {
                                                $cappedAmount = 20;
                                            }
                                            $processingFees = $payment->lc_commission_amount * 0.02;
                                            $processingFees = $processingFees > $cappedAmount ? round($cappedAmount, 1) : round($processingFees, 1);
                                            $amount = "$".number_format($payment->lc_commission_amount - $processingFees, 2);
                                        }
                                        else
                                        {
                                            $amount = "$".number_format($payment->lc_commission_amount, 2);
                                        }

                                    @endphp
                                    {{ $amount ?? 0 }}
                                </div>
    <td class="text-end pe-0">
    <a> {{ $payment->paid_date ?? "-" }}</a>
    </td>
       @if($payment->status == \App\Models\PaymentHistory::PENDING)
                                    <div class="orderDatatable-status d-inline-block">
                                        <span class="order-bg-opacity-warning  text-warning rounded-pill active">Pending</span>
                                    </div>
                                @elseif($payment->status == \App\Models\PaymentHistory::PAID)
                                    <div class="orderDatatable-status d-inline-block">
                                        <span class="order-bg-opacity-primary  text-primary rounded-pill active">Paid</span>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group atbd-button-group btn-group-normal" role="group ">
                                    @php
                                        $status = $payment->status == \App\Models\Transaction::STATUS_PAID ? "paid" : "pending";
                                    @endphp
                                    <a href="{{ route("publisher.reports.transactions.list") }}?payment_id={{ $payment->id }}&r_name={{ $status }}" type="button" class="btn  btn-xs btn-outline-dark">Transactions</a>
                                    @if($payment->status == \App\Models\PaymentHistory::PAID)
                                        <a href="{{ route("publisher.payments.invoice", ['payment_history' => $payment->id]) }}" type="button" class="btn btn-xs btn-outline-dark">Invoice</a>
                                    @endif
                                </div>
                            </td>
                    
                        
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="12" class="text-center">
                    <small>No Payments Exist</small>
                </td>
            </tr>
        @endif
    </tbody>
    
    <script>
    function close_modal(ad_id){
        console.log(ad_id)
        $('#kt_modal_apply_data_'+ad_id).hide();
         $('.modal-backdrop').remove(); // For Bootstrap or similar systems

    // Remove body scroll locking (if applicable)
    $('body').removeClass('modal-open');
    $('body').css('overflow', '');
    }
</script>
</table>
