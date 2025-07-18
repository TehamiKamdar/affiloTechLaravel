<?php

namespace App\Plugins\Traderdoubler;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\Coupon as CouponModel;

class Coupon extends Base
{
    public function callApi($param)
    {
        $couponsData = $this->sendTradedoublerGetCouponByTokenRequest($param['token'] ?? null);
        if(!empty($couponsData) && count($couponsData))
        {
            $this->storeData($couponsData);
        }
    }

    public function storeData($couponData)
    {
        $vars = $this->getTradedoublerCouponStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];

        foreach ($couponData as $response)
        {
            $advertiser = Advertiser::where('source', $source)->where("advertiser_id", $response['programId'])->first();

            if($advertiser) {

//                    Methods::customTradedoubler($module, "ADVERTISER ID: {$response['programId']} & COUPON ID: {$response['id']} FETCHING START");

                CouponModel::updateOrCreate([
                    'internal_advertiser_id'   => $advertiser->id,
                    'promotion_id'   => $response['id'],
                    'source' => $source
                ],[
                    'sid'                       => $advertiser->sid,
                    'advertiser_id'             => $response['programId'],
                    'advertiser_name'           => $response['programName'],
                    'advertiser_status'         => "active",
                    'promotion_id'              => $response['id'],
                    'type'                      => isset($response['code']) && $response['code'] ? "voucher" : "promotion",
                    'title'                     => $response['title'],
                    'description'               => $response['description'],
                    'start_date'                => $response['startDate'],
                    'end_date'                  => $response['endDate'],
                    'url'                       => $advertiser->url,
                    'url_tracking'              => $response['defaultTrackUri'],
                    "code"                      => $response['code'] ?? null,
                    "exclusive"                 => $response['exclusive'] ?? false,
                ]);

//                    Methods::customTradedoubler(null, "ADVERTISER ID: {$response['programId']} & COUPON ID: {$response['id']} FETCHING END");

            }
        }
    }

}
