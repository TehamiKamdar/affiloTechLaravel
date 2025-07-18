<?php

namespace App\Plugins\Awin;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\Coupon as CouponModel;

class Coupon extends Base
{
    public function callApi($params)
    {
        $couponsData = $this->sendAwinCouponRequest($params['page']);
        if (isset($couponsData['data']) && isset($couponsData['pagination']['total'])) {
            $this->storeData($couponsData['data']);
        }
    }

    public function storeData($couponData)
    {
        $vars = $this->getAwinCouponStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];

        foreach ($couponData as $response)
        {
            $advertiser = Advertiser::where('source', $source)->where("advertiser_id", $response['advertiser']['id'])->first();

            $regions = ["all"];
            if(!$response['regions']["all"])
            {
                $regions = array_column($response['regions']["list"], "countryCode");
            }

            if($advertiser) {

//                    Methods::customAwin($module, "ADVERTISER ID: {$response['advertiser']['id']} & COUPON ID: {$response['promotionId']} FETCHING START");

                CouponModel::updateOrCreate([
                    'internal_advertiser_id'   => $advertiser->id,
                    'promotion_id'   => $response['promotionId'],
                    'source' => $source
                ],[
                    'sid'                       => $advertiser->sid,
                    'advertiser_id'             => $response['advertiser']['id'],
                    'advertiser_name'           => $response['advertiser']['name'],
                    'advertiser_status'         => $response['advertiser']['joined'] ? "active" : "disable",
                    'promotion_id'              => $response['promotionId'],
                    'type'                      => $response['type'],
                    'title'                     => $response['title'],
                    'description'               => $response['description'],
                    'terms'                     => $response['terms'],
                    'start_date'                => $response['startDate'],
                    'end_date'                  => $response['endDate'],
                    'url'                       => $response['url'],
                    'url_tracking'              => $response['urlTracking'],
                    'date_added'                => $response['dateAdded'],
                    'campaign'                  => $response['campaign'],
                    'regions'                   => $regions,
                    "code"                      => $response['voucher']['code'] ?? null,
                    "exclusive"                 => $response['voucher']['exclusive'] ?? false,
                    "source"                    => $advertiser->source
                ]);

//                    Methods::customAwin(null, "ADVERTISER ID: {$response['advertiser']['id']}  & COUPON ID: {$response['promotionId']} FETCHING END");

            }
        }
    }

}
