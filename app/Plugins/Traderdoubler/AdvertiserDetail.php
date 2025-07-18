<?php

namespace App\Plugins\Traderdoubler;

use App\Helper\Static\Vars;
use App\Helper\Static\Methods;
use App\Models\Advertiser as AdvertiserModel;
use App\Models\Commission;

class AdvertiserDetail extends Base
{
    const DESCRIPTION = 2;
    const SHORT_DESCRIPTION = 3;
    const POLICIES = 46;
    const TERMS = 34;

    public function prepareData($data)
    {
        $vars = $this->getTradedoublerAdvertiserDetailStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];

        if(isset($data['advertiser_id'])) {

            $response = $this->sendTradedoublerGetAdvertiserByIDRequest($data);

            if(isset($response['id'])) {


//                        Methods::customTradedoubler($module, "ADVERTISER ID: {$response['id']} FETCHING START");

                $advertiser = AdvertiserModel::where([
                    'advertiser_id'   =>    $response['id'],
                    'source'          =>    $source
                ])->first();

                if($advertiser)
                {
//                        $description = $shortDescription = $policies = null;
//                        foreach ($response['texts'] ?? [] as $text)
//                        {
//                            if($text['textTypeId'] == self::DESCRIPTION)
//                            {
//                                $description = $text['text'];
//                            }
//                            elseif ($text['textTypeId'] == self::SHORT_DESCRIPTION)
//                            {
//                                $shortDescription = $text['text'];
//                            }
//                            elseif ($text['textTypeId'] == self::TERMS && $policies == null)
//                            {
//                                $policies = $text['text'];
//                            }
//                            elseif ($text['textTypeId'] == self::POLICIES)
//                            {
//                                $policies = $text['text'];
//                            }
//                        }

                    $advertiser->update([
                        "url" => $response['homePage'],
                        "currency_code" => $response['currencyCode'],
                        "deeplink_enabled" => $response['deepLinking'] ?? 0,
                        "epc" => $response['avgEpc'] ?? 0,
                        "average_payment_time" => $response['avgPaymentDays'],
//                            "description" => $description,
//                            "short_description" => $shortDescription,
//                            "program_policies" => $policies
                    ]);

                    $commissions = $response['segmentTariffs'][0]['tariffs'] ?? [];
                    $this->storeCommission($advertiser, $commissions);

//                            Methods::customTradedoubler(null, "ADVERTISER ID: {$response['id']} FETCHING END");

                }

            }
        }

        $this->changeJobTime();
    }

    private function storeCommission($advertiser, $commissions)
    {
        $vars = $this->getTradedoublerAdvertiserDetailStaticVar();
        $commissionLinkscircle = $vars['commission_linkscircle'];
        $defaultCreated = $vars['default_created'];
        $createdBy = $vars['created_by'];

        $commissionCheck = Commission::where('advertiser_id', $advertiser->id)->count();
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
        foreach ($commissions as $commission)
        {
            if($commission['eventName'] == "Transaction Inquiry")
                continue;

            $rate = $commission['percentageFee'] ? $commission['percentageFee'] : $commission['fixedFee'];
            $type = "percentage";

            Commission::updateOrCreate([
                "condition" => $commission['eventName'],
                'advertiser_id'   => $advertiser->id,
                "created_by"      => $createdBy
            ],[
                "rate" => $rate,
                "type" => $type,
                "info"            => "Network Rate"
            ]);

            if($advertiser->rate != $rate) {

                $advertiser->update([
                    "commission" => $rate,
                    "commission_type" => "%"
                ]);

            }

        }
    }

}
