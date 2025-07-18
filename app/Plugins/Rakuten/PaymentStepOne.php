<?php

namespace App\Plugins\Rakuten;

use App\Helper\Static\Vars;
use App\Models\FetchDailyData;
use App\Traits\Main;

class PaymentStepOne extends Base
{
    use Main;

    public function callApi($data): void
    {

        $token = $data['security_token'];
        $start = $data['start'];
        $end = $data['end'];
        $source = Vars::RAKUTEN;

        $payments = $this->sendRakutenPaymentRequest($start, $end, $token);

        if (\preg_match("/You cannot request/", $payments)) {
            info("Reached the limit....");
            throw new \Exception ("Reached the limit");
        }
        $paymentLines = \str_getcsv($payments, "\n");
        $number = \count($paymentLines);
        for ($j = 1; $j < $number; $j++) {

            $paymentData = \str_getcsv($paymentLines [$j], ",");

            $paymentID = $paymentData[0] ?? 0;

            FetchDailyData::updateOrCreate([
                "path" => "PaymentStepTwoJob",
                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                "start_date" => $start,
                "end_date" => $end,
                "queue" => Vars::RAKUTEN_ON_QUEUE,
                "source" => $source,
                "key" => $paymentID,
                "type" => Vars::ADVERTISER
            ], [
                "name" => "Rakuten Payment Step Two Job",
                "payload" => json_encode(['start' => $start, 'end' => $end, 'security_token' => $token, "payment_id" => $paymentID]),
                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);

        }

    }
}
