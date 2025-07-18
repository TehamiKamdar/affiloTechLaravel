<?php

namespace App\Traits\Provider\Offer;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\NetworkAdvertiserExtraFetchStatusUpdateJob;
use App\Jobs\NetworkOfferFetchStatusUpdateJob;
use App\Jobs\Rakuten\CouponJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use Plugins\Rakuten\RakutenTrait;

trait Rakuten
{
    use RakutenTrait;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handleRakuten($months)
    {
        $vars = $this->getRakutenStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $advertiserActive = $vars['available'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];
        $timer = $vars['timer'];
        $timing = $vars['timing'];

//            Methods::customRakuten($module, $startMsg);

        $advertisers = Advertiser::select('advertiser_id')->where("source", $source)->where("is_available", $advertiserActive)->get();

        foreach ($advertisers->pluck('advertiser_id')->chunk(20)->toArray() as $advertiser)
        {
//                $delay = $timer * $timing;
//                CouponJob::dispatch($advertiser['advertiser_id'])
//                    ->onQueue($queue)
//                    ->delay($delay);

            FetchDailyData::updateOrCreate([
                "path" => "RakutenCouponJob",
                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                "payload" => json_encode(['ids' => $advertiser]),
                "queue" => $queue,
                "source" => $source,
                "type" => Vars::COUPON
            ], [
                "name" => "Rakuten Coupon Job",
                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);

        }

        $this->offerDataCompleteStatus($queue, $source);

//            Methods::customRakuten($module, $endMsg);

    }

    private function getRakutenStaticVar(): array
    {
        $source = Vars::RAKUTEN;
        $name = strtoupper($source);
        $queue = Vars::RAKUTEN_ON_QUEUE;
        $activeAvailable = Vars::ADVERTISER_AVAILABLE;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COUPON COMMAND",
            "queue_name" => $queue,
            "available" => $activeAvailable,
            "timer" => 1,
            "timing" => 60,
            "start_msg" => "FETCHING OF COUPONS FOR THE ADVERTISER HAS STARTED.",
            "end_msg" => "FETCHING OF COUPONS FOR THE ADVERTISER HAS BEEN COMPLETED.",
        ];
    }
}
