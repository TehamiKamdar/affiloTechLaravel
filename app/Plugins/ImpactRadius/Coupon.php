<?php

namespace App\Plugins\ImpactRadius;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\Coupon as CouponModel;

class Coupon extends Base
{

    public function storeData($couponData)
    {
        $vars = $this->getImpactCouponStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];

        foreach ($couponData as $response)
        {
            $advertiser = Advertiser::where('source', $source)->where("advertiser_id", $response['CampaignId'])->first();

            if($advertiser) {

//                    Methods::customImpactRadius($module, "ADVERTISER ID: {$response['CampaignId']} & COUPON ID: {$response['Id']} FETCHING START");

                CouponModel::updateOrCreate([
                    'internal_advertiser_id'   => $advertiser->id,
                    'promotion_id'   => $response['Id'],
                    'source' => $source
                ],[
                    'advertiser_id'             => $advertiser->advertiser_id,
                    'sid'                       => $advertiser->sid,
                    'advertiser_name'           => $advertiser->name,
                    'advertiser_status'         => $advertiser->status ? "active" : "disable",
                    'type'                      => $response['DefaultPromoCode'] ? "voucher" : "promotion",
                    'title'                     => $response['Name'],
                    'description'               => $response['Description'],
                    'start_date'                => $response['StartDate'],
                    'end_date'                  => $response['EndDate'],
                    'url'                       => $advertiser->url,
                    'date_added'                => $response['DateCreated'],
                    "code"                      => $response['DefaultPromoCode'] ?? null,
                ]);

//                    Methods::customImpactRadius(null, "ADVERTISER ID: {$response['CampaignId']} & COUPON ID: {$response['CampaignId']} FETCHING END");

            }
        }
    }
}
