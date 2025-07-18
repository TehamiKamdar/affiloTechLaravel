<?php

namespace App\Plugins\Admitad;

use App\Helper\Static\Methods;
use App\Models\Advertiser;
use App\Models\Coupon as CouponModel;

class Coupon extends Base
{
    public function callApi($param)
    {
        $couponsData = $this->sendAdmitadCouponRequest($param['website_id'], $param['offset']);

        if(isset($couponsData['results']) && count($couponsData['results']))
        {
            foreach($couponsData['results'] as $data) {
                $this->storeData($data);
            }
        }
    }

    public function storeData($response)
    {
        $vars = $this->getAdmitadCouponStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];

        $advertiser = Advertiser::where('source', $source)->where("advertiser_id", $response['campaign']['id'])->first();

        $regions = ["all"];
        if(isset($response['regions'][0]) && $response['regions'][0] == "all")
        {
            $regions = $response['regions'];
        }
        elseif (!isset($response['regions'][0]))
        {
            $regions = [];
        }

        if($advertiser) {

//                    Methods::customAdmitad($module, "ADVERTISER ID: {$response['advertiser']['id']} & COUPON ID: {$response['promotionId']} FETCHING START");


            CouponModel::updateOrCreate([
                'internal_advertiser_id'   => $advertiser->id,
                'promotion_id'   => $response['id'],
                'source' => $source
            ],[
                'sid'                       => $advertiser->sid,
                'advertiser_id'             => $response['campaign']['id'],
                'advertiser_name'           => $response['campaign']['name'],
                'advertiser_status'         => $advertiser->status ? "active" : "disable",
                'promotion_id'              => $response['id'],
                'type'                      => $response['promocode'] ? "voucher" : "promotion",
                'title'                     => $response['name'],
                'description'               => $response['description'],
                'start_date'                => $response['date_start'],
                'end_date'                  => $response['date_end'],
                'url'                       => $advertiser->url,
                'url_tracking'              => $response['goto_link'],
                'regions'                   => $regions,
                "code"                      => $response['promocode'] ?? null,
                "exclusive"                 => $response['exclusive'] ?? false,
                "source"                    => $advertiser->source
            ]);

//                    Methods::customAdmitad(null, "ADVERTISER ID: {$response['advertiser']['id']}  & COUPON ID: {$response['promotionId']} FETCHING END");


        }
    }
}
