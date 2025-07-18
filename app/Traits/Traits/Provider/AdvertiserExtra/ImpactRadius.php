<?php

namespace App\Traits\Provider\AdvertiserExtra;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\AdvertiserDeleteFromNetwork;
use App\Jobs\AdvertiserNotFoundToPending;
use App\Jobs\ImpactRadius\AdvertiserDetailJob;
use App\Jobs\ImpactRadius\AdvertiserImageUploadJob;
use App\Jobs\ImpactRadius\FetchCouponJob;
use App\Jobs\NetworkAdvertiserExtraFetchStatusUpdateJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use App\Plugins\ImpactRadius\ImpactRadiusTrait;

trait ImpactRadius
{
    use ImpactRadiusTrait;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handleImpactRadius($months)
    {
        $vars = $this->getImpactStaticVar();
        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $timer = $vars['timer'];
        $timing = $vars['timing'];
        $available = $vars['available'];
        $limit = $vars['limit'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

//        Advertiser::select('advertiser_id')->where("source", $source)->where("is_available", $available)->chunk($limit, function ($advertisers) use ($source, $queue) {
//
//            $logoFetchIDz = $advertisers
//                            ->where(function($query) {
//                                $query->orWhereNull("logo");
//                                $query->orWhereNull("logo", "");
//                                $query->orWhereNull("logo", null);
//                            })
//                            ->pluck('advertiser_id');
//
//            $ids = $advertisers->pluck("advertiser_id");
//
//            FetchDailyData::updateOrCreate([
//                "path" => "ImpactAdvertiserDetailJob",
//                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
//                "payload" => json_encode(["ids" => $ids]),
//                "queue" => $queue,
//                "source" => $source,
//                "type" => Vars::ADVERTISER_DETAIL
//            ], [
//                "name" => "Impact Advertiser Detail Job",
//                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
//                "sort" => $this->setSortingFetchDailyData($source),
//            ]);
//
//            FetchDailyData::updateOrCreate([
//                "path" => "ImpactAdvertiserImageUploadJob",
//                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
//                "payload" => json_encode(['ids' => $logoFetchIDz]),
//                "queue" => $queue,
//                "source" => $source,
//                "type" => Vars::ADVERTISER_DETAIL
//            ], [
//                "name" => "Impact Advertiser Image Upload Job",
//                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
//                "sort" => $this->setSortingFetchDailyData($source),
//            ]);
//
////                $delay = $timer * $timing;
////                AdvertiserDetailJob::dispatch($ids)->onQueue($queue)->delay($delay);
////                $timer++;
//
////                $delay = $timer * $timing;
////                AdvertiserImageUploadJob::dispatch($logoFetchIDz)->onQueue($queue)->delay($delay);
////                $timer++;
//
//        });

//        $this->advertiserNotFoundNDelete($queue, $source);
        $this->advertiserExtraDataCompleteStatus($queue, $source);

    }

    private function getImpactStaticVar(): array
    {
        $source = Vars::IMPACT_RADIUS;
        $name = strtoupper($source);
        $queue = Vars::IMPACT_RADIUS_ON_QUEUE;
        $limit = Vars::LIMIT_20;
        $available = Vars::ADVERTISER_AVAILABLE;

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
