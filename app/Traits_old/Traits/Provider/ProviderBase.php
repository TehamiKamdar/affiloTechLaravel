<?php

namespace App\Traits\Provider;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\FetchDailyData;
use App\Models\NetworkFetchData;

trait ProviderBase
{
    public function transactionStatusUpdate($name, $months)
    {
        $updateData = [];

        if($months == 1)
        {
            $updateData = [
                'advertiser_transaction_short_status' => NetworkFetchData::COMPLETED,
                "last_updated_advertiser_transaction_short" => now()->format("Y-m-d H:i:s")
            ];
            $msg = "SHORT TRANSACTION";
        }
        elseif($months > 1)
        {
            $updateData = [
                'advertiser_transaction_schedule_status' => NetworkFetchData::COMPLETED,
                "last_updated_advertiser_transaction" => now()->format("Y-m-d")
            ];
            $msg = "TRANSACTION";
        }

        Methods::customDefault("{$name} {$msg}", "{$name} COMPLETED {$msg} FETCH | MONTHS: {$months}");

        if(count($updateData))
        {
            NetworkFetchData::where("name", $name)->update($updateData);
        }
    }
    public function paymentStatusUpdate($name, $months)
    {

        NetworkFetchData::where("name", $name)->update([
            'advertiser_payment_status' => NetworkFetchData::COMPLETED,
            "last_updated_advertiser_payment" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
        ]);

    }

    public function advertiserNotFoundNDelete($queue, $source)
    {
        FetchDailyData::updateOrCreate([
            "path" => "AdvertiserNotFoundToPending",
            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
            "queue" => $queue,
            "source" => $source,
            "type" => Vars::ADVERTISER_DETAIL
        ], [
            "name" => "Advertiser Not Found To Pending",
            "payload" => json_encode(['source' => $source]),
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
            "sort" => $this->setSortingFetchDailyData($source),
            "status" => 1,
            "is_processing" => 0
        ]);

        FetchDailyData::updateOrCreate([
            "path" => "AdvertiserDeleteFromNetwork",
            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
            "queue" => $queue,
            "source" => $source,
            "type" => Vars::ADVERTISER_DETAIL
        ], [
            "name" => "Advertiser Delete From Network",
            "payload" => json_encode(['source' => $source]),
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
            "sort" => $this->setSortingFetchDailyData($source),
            "status" => 1,
            "is_processing" => 0
        ]);
    }

    public function advertiserDataCompleteStatus($queue, $source)
    {
        FetchDailyData::updateOrCreate([
            "path" => "NetworkAdvertiserFetchStatusUpdateJob",
            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
            "queue" => $queue,
            "source" => $source,
            "type" => Vars::ADVERTISER
        ], [
            "name" => "Network Advertiser Fetch Status Update Job",
            "payload" => json_encode(['source' => $source]),
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
            "sort" => $this->setSortingFetchDailyData($source),
            "status" => 1,
            "is_processing" => 0
        ]);
    }

    public function advertiserExtraDataCompleteStatus($queue, $source)
    {
        FetchDailyData::updateOrCreate([
            "path" => "NetworkAdvertiserExtraFetchStatusUpdateJob",
            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
            "queue" => $queue,
            "source" => $source,
            "type" => Vars::ADVERTISER_DETAIL
        ], [
            "name" => "Network Advertiser Extra Fetch Status Update Job",
            "payload" => json_encode(['source' => $source]),
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
            "sort" => $this->setSortingFetchDailyData($source),
            "status" => 1,
            "is_processing" => 0
        ]);
    }

    public function offerDataCompleteStatus($queue, $source)
    {
        FetchDailyData::updateOrCreate([
            "path" => "NetworkOfferFetchStatusUpdateJob",
            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
            "queue" => $queue,
            "source" => $source,
            "type" => Vars::COUPON
        ], [
            "name" => "Network Offer Fetch Status Update Job",
            "payload" => json_encode(['source' => $source]),
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
            "sort" => $this->setSortingFetchDailyData($source),
            "status" => 1,
            "is_processing" => 0
        ]);
    }
}
