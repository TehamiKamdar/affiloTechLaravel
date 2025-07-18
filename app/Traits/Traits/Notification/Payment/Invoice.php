<?php

namespace App\Traits\Notification\Payment;

use App\Helper\Static\Vars;
use App\Models\EmailJob;
use App\Models\Notification;
use Carbon\Carbon;

trait Invoice
{
    use Base;

    public function inoviceNotification($history)
    {
        $paidDate = Carbon::parse($history->paid_date)->format("d-m-Y");
        $lcCommission = "$".number_format($history->lc_commission_amount, 2);
        $url = route("publisher.payments.invoice", ['payment_history' => $history->id]);

        Notification::updateOrCreate([
            "publisher_id" => $history->publisher_id,
            "type" => "New Invoice Generated",
            "category" => "LinksCircle Updates",
            "notification_header" => "New payment invoice has been generated into your account.",
            "header" => "New payment invoice has been generated into your account. <br />Payment of commissions up to {$paidDate} has been Paid... Read More</span>",
            "content" => "<p class='mb-20'>Hello,
                 <br /> <br />
                 New invoice (#{$history->invoice_id}) has been generated into your account for the payment of commissions up to {$paidDate} i.e {$lcCommission}.
                 <br /> <br />
                 You will receive your payment within 12-24 business hours.
                 <br /><br />
                 Please review your invoice here: <a href='{$url}'>View to Invoice</a>
                 <br /> <br />
                 Note: Complete your billing info & payment method in your account to receive funds.
                 <br /> <br />
                Regards,<br />
                LinksCircle</p>
                ",
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT)
        ]);

        EmailJob::create([
            'name' => 'Invoice Generate Job',
            'path' => 'InvoiceGenerateJob',
            'payload' => json_encode($history->with('user')->first()),
            'date' => now()->format(Vars::CUSTOM_DATE_FORMAT)
        ]);

    }
}
