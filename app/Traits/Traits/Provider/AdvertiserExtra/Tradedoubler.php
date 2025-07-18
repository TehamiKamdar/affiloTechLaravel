<?php

namespace App\Traits\Provider\AdvertiserExtra;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\AdvertiserDeleteFromNetwork;
use App\Jobs\AdvertiserNotFoundToPending;
use App\Jobs\NetworkAdvertiserExtraFetchStatusUpdateJob;
use App\Jobs\Tradedoubler\AdvertiserDetailJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use App\Plugins\Traderdoubler\TradedoublerTrait;

trait Tradedoubler
{
    use TradedoublerTrait;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handleTradedoubler($months)
    {
        $vars = $this->getTradedoublerStaticVar();
        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $timer = $vars['timer'];
        $timing = $vars['timing'];
        $available = $vars['available'];
        $limit = $vars['limit'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

//            Methods::customTradedoubler($module, $startMsg);

//            Advertiser::select('advertiser_id')->where("source", $source)->where("is_available", $available)->chunk($limit, function ($advertisers) use ($source, $queue) {
//
//                $ids = $advertisers->pluck("advertiser_id");
//
//                FetchDailyData::updateOrCreate([
//                    "path" => "TradedoublerAdvertiserDetailJob",
//                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
//                    "payload" => json_encode(['ids' => $ids]),
//                    "queue" => $queue,
//                    "source" => $source,
//                    "type" => Vars::ADVERTISER_DETAIL
//                ], [
//                    "name" => "Tradedoubler Advertiser Detail Job",
//                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
//                    "sort" => $this->setSortingFetchDailyData($source),
//                ]);
//
//
////                $delay = $timer * $timing;
////                AdvertiserDetailJob::dispatch($ids)->onQueue($queue)->delay($delay);
////                $timer++;
//
//            });

//            $this->advertiserNotFoundNDelete($queue, $source);
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

//            $delay = $timer * $timing;
//            NetworkAdvertiserExtraFetchStatusUpdateJob::dispatch($source)->onQueue($queue)->delay($delay);

//            Methods::customTradedoubler($module, $endMsg);

    }

    private function getTradedoublerStaticVar(): array
    {
        $source = Vars::TRADEDOUBLER;
        $name = strtoupper($source);
        $queue = Vars::TRADEDOUBLER_ON_QUEUE;
        $available = Vars::ADVERTISER_AVAILABLE;
        $limit = Vars::LIMIT_10;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER EXTRA COMMAND",
            "queue_name" => $queue,
            "timer" => 1,
            "timing" => 60,
            "available" => $available,
            "limit" => $limit,
            "start_msg" => "FETCHING OF ADDITIONAL DETAILS FOR THE ADVERTISER HAS STARTED.",
            "end_msg" => "FETCHING OF ADDITIONAL DETAILS FOR THE ADVERTISER HAS BEEN COMPLETED.",
        ];
    }

}
