<?php

namespace App\Traits\Provider\Offer;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Awin\CouponJob;
use App\Jobs\NetworkOfferFetchStatusUpdateJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use Plugins\Awin\AwinTrait;

trait Awin
{
    use AwinTrait;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handleAwin($months)
    {

        $vars = $this->getAwinStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $advertiserActive = $vars['available'];
        $limit = $vars['limit'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

//            Methods::customAwin($module, $startMsg);
            $couponsData = $this->sendAwinCouponRequest();
            $total = ceil(floatval(intval($couponsData['pagination']['total']) / intval($couponsData['pagination']['pageSize'])));
            for ($page = 1; $page <= $total; $page++)
            {

                FetchDailyData::updateOrCreate([
                    "path" => "AwinCouponJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "key" => $page,
                    "queue" => $queue,
                    "source" => $source,
                    "type" => Vars::COUPON
                ], [
                    "name" => "Awin Coupon Job",
                    "payload" => json_encode(['page' => $page, 'total' => $total]),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    "sort" => $this->setSortingFetchDailyData($source),
                ]);

            }

            $this->offerDataCompleteStatus($queue, $source);

//            Advertiser::select("advertiser_id")->where("source", $source)->where("status", $advertiserActive)->chunk($limit, function ($advertisers) use($queue,$couponsData,$page) {
//
//
////                $page = 1;
////                $couponsData = $this->sendAwinCouponRequest($advertisers->pluck("advertiser_id")->toArray(), $page);
////                echo $page;
////                echo "\n";
////                echo "\n";
////                if (isset($couponsData['data']) && isset($couponsData['pagination']['total']))
////                {
////                    CouponJob::dispatch($couponsData['data'])->onQueue($queue);
////                    $total = ceil(floatval($couponsData['pagination']['total'] / $couponsData['pagination']['pageSize']));
////                    for ($page = 2; $page <= $total; $page++)
////                    {
////
////                        $couponsData = $this->sendAwinCouponRequest($page);
////                        CouponJob::dispatch($couponsData['data'] ?? [])->onQueue($queue);
////                        echo $page;
////                        echo "\n";
////                        echo "\n";
////
////                        $this->changeJobTime(5);
////
////                    }
////                }
////
////                $this->changeJobTime(5);
//            });

//            NetworkOfferFetchStatusUpdateJob::dispatch($source)->onQueue($queue);

//            Methods::customAwin($module, $endMsg);

    }

    private function getAwinStaticVar(): array
    {
        $source = Vars::AWIN;
        $name = strtoupper($source);
        $not_available = Vars::ADVERTISER_NOT_AVAILABLE;
        $active = Vars::ADVERTISER_STATUS_ACTIVE;
        $queue = Vars::AWIN_ON_QUEUE;
        $limit = Vars::LIMIT_20;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COUPON COMMAND",
            "queue_name" => $queue,
            "limit" => $limit,
            "not_available" => $not_available,
            "available" => $active,
            "timer" => 1,
            "timing" => 20,
            "start_msg" => "FETCHING OF COUPONS FOR THE ADVERTISER HAS STARTED.",
            "end_msg" => "FETCHING OF COUPONS FOR THE ADVERTISER HAS BEEN COMPLETED.",
        ];
    }
}
