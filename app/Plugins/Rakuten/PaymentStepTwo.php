<?php

namespace App\Plugins\Rakuten;

use App\Helper\Static\Vars;
use App\Models\FetchDailyData;
use App\Traits\Main;

class PaymentStepTwo extends Base
{
    use Main;

    public function callApi($data): void
    {

        $token = $data['security_token'];
        $start = $data['start'];
        $end = $data['end'];
        $paymentID = $data['payment_id'];
        $source = Vars::RAKUTEN;

        $payments = $this->sendRakutenPaymentRequest($start, $end, $token, 2, $paymentID);

        if (\preg_match("/You cannot request/", $payments)) {
            throw new \Exception ("Reached the limit");
        }
        $paymentLines = \str_getcsv($payments, "\n");
        $number = \count($paymentLines);
        for ($j = 1; $j < $number; $j++) {

            $paymentData = \str_getcsv($paymentLines [$j], ",");

            $invoiceID = $paymentData[2] ?? 0;

            FetchDailyData::updateOrCreate([
                "path" => "PaymentStepThreeJob",
                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                "start_date" => $start,
                "end_date" => $end,
                "queue" => Vars::RAKUTEN_ON_QUEUE,
                "source" => $source,
                "key" => $invoiceID,
                "type" => Vars::ADVERTISER
            ], [
                "name" => "Rakuten Payment Step Three Job",
                "payload" => json_encode(['start' => $start, 'end' => $end, 'security_token' => $token, "payment_id" => $paymentID, "invoice_id" => $invoiceID]),
                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);

        }

    }
}
