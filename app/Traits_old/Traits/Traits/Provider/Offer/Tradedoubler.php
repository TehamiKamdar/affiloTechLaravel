<?php

namespace App\Traits\Provider\Offer;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\NetworkOfferFetchStatusUpdateJob;
use App\Jobs\Tradedoubler\CouponJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use Plugins\Traderdoubler\TradedoublerTrait;

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
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

//            Methods::customTradedoubler($module, $startMsg);

            $tokenDataList = $this->sendTradedoublerGetCouponTokenRequest();
            foreach ($tokenDataList['tokens'] ?? [] as $token)
            {
                FetchDailyData::updateOrCreate([
                    "path" => "TradedoublerCouponJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "payload" => json_encode(['token' => $token['token'] ?? null]),
                    "queue" => $queue,
                    "source" => $source,
                    "type" => Vars::COUPON
                ], [
                    "name" => "Tradedoubler Coupon Job",
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    "sort" => $this->setSortingFetchDailyData($source),
                ]);

//                $couponsData = $this->sendTradedoublerGetCouponByTokenRequest($token['token'] ?? null);
//                if(!empty($couponsData) && count($couponsData))
//                {
//                    CouponJob::dispatch($couponsData)->onQueue($queue);
//                }
            }

            $this->offerDataCompleteStatus($queue, $source);

//            Methods::customTradedoubler($module, $endMsg);

    }

    private function getTradedoublerStaticVar(): array
    {
        $source = Vars::TRADEDOUBLER;
        $name = strtoupper($source);
        $queue = Vars::TRADEDOUBLER_ON_QUEUE;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COUPON COMMAND",
            "queue_name" => $queue,
            "start_msg" => "FETCHING OF COUPONS FOR THE ADVERTISER HAS STARTED.",
            "end_msg" => "FETCHING OF COUPONS FOR THE ADVERTISER HAS BEEN COMPLETED.",
        ];
    }
}
