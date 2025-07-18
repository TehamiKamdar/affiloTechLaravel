<?php

namespace App\Plugins\Rakuten;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Commission;
use App\Models\Advertiser;

class AdvertiserDetail extends Base
{
    public function prepareData($data)
    {
        $vars = $this->getRakutenAdvertiserDetailStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];
        $createdBy = $vars['created_by'];
        $commissionLinkscircle = $vars['commission_linkscircle'];
        $defaultCreated = $vars['default_created'];

        if(isset($data['ns1mid']) || isset($data['ns1_mid'])) {

            $id = $data['ns1mid'] ?? $data['ns1_mid'];

//                Methods::customRakuten($module, "ADVERTISER ID: {$id} FETCHING START");

            $terms = $data['ns1offer']['ns1commissionTerms'] ?? $data['ns1_offer']['ns1_commissionTerms'];

            $advertiser = Advertiser::where("source", $source)->where('advertiser_id', $id)->first();
            $commissionCheck = Commission::where('advertiser_id', $advertiser->id ?? null)->count();
            if($commissionCheck == 0)
            {
                $rate = $commissionLinkscircle;
                $type = "percentage";
                Commission::create([
                    'advertiser_id'   => $advertiser->id,
                    "created_by"      => $defaultCreated,
                    "rate"            => $rate,
                    "condition"       => "All Affiliates",
                    "type"            => $type,
                    "info"            => "LinksCircle Rate"
                ]);

                $advertiser->update([
                    "commission" => $rate,
                    "commission_type" => $type
                ]);
            }

            if(str_contains($terms, "above ")) {
                $rate = explode("above ", $terms);
            }
            elseif(str_contains($terms, " | ")) {
                $rate = null;
            } else {
                $rate = floatval($terms);
            }

            if(is_array($rate))
            {
                $rate = array_map(function ($r) {
                    return floatval($r);
                }, $rate);
                $rate = max($rate);
            }

            Commission::updateOrCreate([
                'advertiser_id'   => $advertiser->id,
                "created_by"      => $createdBy
            ],[
                "rate" => $rate,
                "type" => $data['ns1offer']['ns1offerName'] ?? $data['ns1_offer']['ns1_offerName'],
                "info"            => "Network Rate"
            ]);

            if($advertiser->rate <= $rate) {

                $advertiser->update([
                    "commission" => $rate,
                    "commission_type" => str_contains($terms, "%") ? "%" : null
                ]);

            }

//                Methods::customRakuten(null, "ADVERTISER ID: {$id} FETCHING END");

        }
    }
}
