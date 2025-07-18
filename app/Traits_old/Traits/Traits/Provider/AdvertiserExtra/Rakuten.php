<?php

namespace App\Traits\Provider\AdvertiserExtra;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\AdvertiserDeleteFromNetwork;
use App\Jobs\AdvertiserNotFoundToPending;
use App\Jobs\NetworkAdvertiserExtraFetchStatusUpdateJob;
use App\Jobs\Rakuten\AdvertiserRelatedInfoJob;
use App\Jobs\Rakuten\FetchAdvertiserJob;
use App\Jobs\Rakuten\ReCheckIsAvailableJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use Plugins\Rakuten\RakutenTrait;

trait Rakuten
{
    use RakutenTrait;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handleRakuten($months)
    {
        $vars = $this->getRakutenStaticVar();
        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $available = $vars['available'];
        $notAvailable = $vars['not_available'];
        $limit = $vars['limit'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

//            Methods::customRakuten($module, $startMsg);

//        $advertisers = Advertiser::select('advertiser_id')
//            ->where("source", $source)
//            ->where("is_available", $notAvailable)->get()->pluck("advertiser_id");
//        foreach ($advertisers->chunk($limit) as $advertiser)
//        {
//            FetchDailyData::updateOrCreate([
//                "path" => "RakutenReCheckIsAvailableJob",
//                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
//                "payload" => json_encode(['ids' => $advertiser->toArray()]),
//                "queue" => $queue,
//                "source" => $source,
//                "type" => Vars::ADVERTISER_DETAIL
//            ], [
//                "name" => "Re Check Is Available Job",
//                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
//                "sort" => $this->setSortingFetchDailyData($source),
//            ]);
//
////                ReCheckIsAvailableJob::dispatch($advertiser->toArray())
////                    ->onQueue($queue);
//        }
//
//        $advertisers = Advertiser::select('advertiser_id')
//            ->where("source", $source)
//            ->whereNull("click_through_url")
//            ->where("is_available", $available)->get()->pluck("advertiser_id");
//        foreach ($advertisers->chunk($limit) as $advertiser)
//        {
//            FetchDailyData::updateOrCreate([
//                "path" => "RakutenAdvertiserRelatedInfoJob",
//                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
//                "payload" => json_encode(['ids' => $advertiser->toArray()]),
//                "queue" => $queue,
//                "source" => $source,
//                "type" => Vars::ADVERTISER_DETAIL
//            ], [
//                "name" => "Advertiser Related Info Job",
//                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
//                "sort" => $this->setSortingFetchDailyData($source),
//            ]);
//
////                AdvertiserRelatedInfoJob::dispatch($advertiser->toArray())
////                                        ->onQueue($queue);
//        }

//        $this->advertiserNotFoundNDelete($queue, $source);
        $this->advertiserExtraDataCompleteStatus($queue, $source);

//            FetchDailyData::updateOrCreate([
//                "path" => "AdvertiserNotFoundToPending",
//                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
//                "queue" => $queue
//            ], [
//                "name" => "Advertiser Not Found To Pending",
//                "payload" => json_encode(['source' => $source]),
//                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
//            ]);
//
//            FetchDailyData::updateOrCreate([
//                "path" => "AdvertiserDeleteFromNetwork",
//                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
//                "queue" => $queue
//            ], [
//                "name" => "Advertiser Delete From Network",
//                "payload" => json_encode(['source' => $source]),
//                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
//            ]);

//            AdvertiserNotFoundToPending::dispatch($source)->onQueue($queue);
//            AdvertiserDeleteFromNetwork::dispatch($source)->onQueue($queue);

//            NetworkAdvertiserExtraFetchStatusUpdateJob::dispatch($source)
//                                                    ->onQueue($queue);

//            Methods::customRakuten($module, $endMsg);

    }

    private function getRakutenStaticVar(): array
    {
        $source = Vars::RAKUTEN;
        $name = strtoupper($source);
        $queue = Vars::RAKUTEN_ON_QUEUE;
        $available = Vars::ADVERTISER_AVAILABLE;
        $notAvailable = Vars::ADVERTISER_NOT_AVAILABLE;
        $limit = Vars::LIMIT_5;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER EXTRA COMMAND",
            "queue_name" => $queue,
            "available" => $available,
            "not_available" => $notAvailable,
            "limit" => $limit,
            "start_msg" => "FETCHING OF ADDITIONAL DETAILS FOR THE ADVERTISER HAS STARTED.",
            "end_msg" => "FETCHING OF ADDITIONAL DETAILS FOR THE ADVERTISER HAS BEEN COMPLETED.",
        ];
    }

}
