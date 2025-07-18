<?php

namespace App\Traits\Provider\Offer;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\NetworkAdvertiserExtraFetchStatusUpdateJob;
use App\Jobs\ImpactRadius\FetchCouponJob;
use App\Jobs\NetworkOfferFetchStatusUpdateJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use Plugins\ImpactRadius\ImpactRadiusTrait;

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
        $advertiserActive = $vars['available'];
        $limit = $vars['limit'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];
        $timer = $vars['timer'];
        $timing = $vars['timing'];


//            Methods::customImpactRadius($module, $startMsg);

        Advertiser::select("advertiser_id")->where("source", $source)->where("status", $advertiserActive)->chunk($limit, function ($advertisers) use($queue, $source) {

            $ids = $advertisers->pluck("advertiser_id");

            FetchDailyData::updateOrCreate([
                "path" => "ImpactFetchCouponJob",
                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                "payload" => json_encode(['ids' => $ids]),
                "queue" => $queue,
                "source" => $source,
                "type" => Vars::COUPON
            ], [
                "name" => "Impact Coupon Job",
                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);

//                $delay = $timer * $timing;
//                FetchCouponJob::dispatch($ids)->onQueue($queue)->delay($delay);
//                $timer++;

        });

        $this->offerDataCompleteStatus($queue, $source);

//            $delay = $timer * $timing;
//            NetworkOfferFetchStatusUpdateJob::dispatch($source)->onQueue($queue)->delay($delay);

//            Methods::customImpactRadius($module, $endMsg);

    }

    private function getImpactStaticVar(): array
    {
        $source = Vars::IMPACT_RADIUS;
        $name = strtoupper($source);
        $queue = Vars::IMPACT_RADIUS_ON_QUEUE;
        $activeAvailable = Vars::ADVERTISER_AVAILABLE;
        $limit = Vars::LIMIT_100;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COUPON COMMAND",
            "queue_name" => $queue,
            "limit" => $limit,
            "available" => $activeAvailable,
            "timer" => 1,
            "timing" => 60,
            "start_msg" => "FETCHING OF COUPONS FOR THE ADVERTISER HAS STARTED.",
            "end_msg" => "FETCHING OF COUPONS FOR THE ADVERTISER HAS BEEN COMPLETED.",
        ];
    }
}
