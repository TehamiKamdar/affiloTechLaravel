<?php

namespace App\Traits\Provider\Offer;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\NetworkOfferFetchStatusUpdateJob;
use App\Models\FetchDailyData;
use App\Plugins\Admitad\AdmitadTrait;

trait Admitad
{
    use AdmitadTrait;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handleAdmitad($months)
    {
        $vars = $this->getAdmitadStaticVar();

        $source = $vars['source'];
        $module = $vars['module_name'];
        $wid = $vars['wid'];
        $offset = $vars['offset'];
        $queue = $vars['queue_name'];
        $limit = $vars['offer_limit'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

//            Methods::customAdmitad($module, $startMsg);

            $couponsData = $this->sendAdmitadCouponRequest($wid, $offset);

            if(isset($couponsData["_meta"]['count']))
            {
                for ($job = 0; $job < ceil($couponsData["_meta"]['count'] / $limit); $job++)
                {
                    $offset = $job * $limit;
                    FetchDailyData::updateOrCreate([
                        "path" => "AdmitadCouponJob",
                        "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                        "website_id" => $wid,
                        "offset" => $offset,
                        "queue" => $queue,
                        "source" => $source,
                        "type" => Vars::COUPON
                    ], [
                        "name" => "Admitad Coupon Job",
                        "payload" => json_encode(["offset" => $offset, "website_id" => $wid]),
                        "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                        "sort" => $this->setSortingFetchDailyData($source),
                    ]);

                }
            }

            $this->offerDataCompleteStatus($queue, $source);

//            while ($fetchLoop) {
//                echo $offset;
//                echo "\n\n";
//
//                $couponsData = $this->sendAdmitadCouponRequest($wid, $offset);
//
//                if(isset($couponsData['results']) && count($couponsData['results']))
//                {
//                    CouponJob::dispatch($couponsData['results'])->onQueue($queue);
//                }
//                else
//                {
//                    $fetchLoop = $vars['fetch_loop_false'];
//                }
//                $offset+=20;
//            }

//            NetworkOfferFetchStatusUpdateJob::dispatch($source)->onQueue($queue);

//            Methods::customAdmitad($module, $endMsg);

    }

    private function getAdmitadStaticVar(): array
    {
        $source = Vars::ADMITAD;
        $name = strtoupper($source);
        $queue = Vars::ADMITAD_ON_QUEUE;
        $notAvailable = Vars::ADVERTISER_NOT_AVAILABLE;
        $configs = $this->getAdmitadConfigData();
        $wid = $configs["ad_space_id"];

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COUPON COMMAND",
            "queue_name" => $queue,
            "not_available" => $notAvailable,
            "fetch_loop_true" => true,
            "fetch_loop_false" => false,
            "offset" => 0,
            "offer_limit" => Vars::ADMITAD_COUPON_LIMIT,
            "wid" => $wid,
            "start_msg" => "FETCHING OF COUPONS FOR THE ADVERTISER HAS STARTED.",
            "end_msg" => "FETCHING OF COUPONS FOR THE ADVERTISER HAS BEEN COMPLETED.",
        ];
    }
}
