<?php

namespace App\Plugins\Rakuten;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\Coupon as CouponModel;
use App\Traits\RequestTrait;

class Coupon extends Base
{

    protected $links = [];

    public function storeCoupon($advertiser): void
    {
        $vars = $this->getRakutenCouponStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];

        $page = 1;
        $total = 1;
        do {
            echo "ADVERTISER ID: {$advertiser->advertiser_id} | PAGE: " .  $page;
            echo "\n\n";
            $couponsData = $this->sendRakutenGetCouponByAdvertiserIDRequest($advertiser->advertiser_id, $page);
            $couponsData = $this->soapXML2JSON($couponsData);
            if(isset($couponsData['link']) && count($couponsData['link']))
            {
                foreach ($couponsData["link"] as $response)
                {
                    if(isset($response['clickurl']))
                    {

                        $queryParms = [];
                        $url = parse_url($response['clickurl'], PHP_URL_QUERY);
                        parse_str($url, $queryParms);

//                            Methods::customRakuten($module, "ADVERTISER ID: {$response['advertiserid']} & COUPON ID: {$queryParms['offerid']} FETCHING START");

                        CouponModel::updateOrCreate([
                            'internal_advertiser_id'   => $advertiser->id,
                            'promotion_id'   => $queryParms['offerid'],
                            'source' => $source
                        ],[
                            'sid'                       => $advertiser->sid,
                            'advertiser_id'             => $response['advertiserid'],
                            'advertiser_name'           => $response['advertisername'],
                            'advertiser_status'         => $advertiser->status ? "active" : "disable",
                            'type'                      => isset($response['couponcode']) ? "voucher" : "promotion",
                            'title'                     => $response['offerdescription'],
                            'start_date'                => $response['offerstartdate'],
                            'end_date'                  => $response['offerenddate'],
                            'url'                       => $advertiser->url,
                            'url_tracking'              => $response['clickurl'],
                            'code'                      => $response['couponcode'] ?? null,
                        ]);

                        $this->links[] = $response['clickurl'];

//                            Methods::customRakuten(null, "ADVERTISER ID: {$response['advertiserid']} & COUPON ID: {$queryParms['offerid']} FETCHING END");

                    }
                }
            }
            $total = $couponsData['TotalPages'];
            $page++;
        }
        while ($page < $total);

        if(count($this->links) && (empty($advertiser->click_through_url) || !in_array($advertiser->click_through_url, $this->links)))
        {
            Advertiser::where("advertiser_id", $advertiser->advertiser_id)->update([
                'click_through_url' => $this->links[0]
            ]);
        }

        $this->links = [];
    }
}
