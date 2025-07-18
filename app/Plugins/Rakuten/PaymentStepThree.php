<?php

namespace App\Plugins\Rakuten;

use App\Helper\Static\Vars;
use App\Models\FetchDailyData;
use App\Traits\Main;

class PaymentStepThree extends Base
{
    use Main;

    public function callApi($data): void
    {

        $token = $data['security_token'];
        $start = $data['start'];
        $end = $data['end'];
        $paymentID = $data['payment_id'];
        $invoiceID = $data['invoice_id'];

        $payments = $this->sendRakutenPaymentRequest($start, $end, $token, 3, $paymentID, $invoiceID);

        if (\preg_match("/You cannot request/", $payments)) {
            throw new \Exception ("Reached the limit");
        }
        $paymentLines = \str_getcsv($payments, "\n");
        $number = \count($paymentLines);
        for ($j = 1; $j < $number; $j++) {

            $paymentData = \str_getcsv($paymentLines [$j], ",");

            \App\Models\Transaction::where("order_ref",$paymentData[4])->update([
                "paid_to_publisher" => 1
            ]);
        }

    }
}
