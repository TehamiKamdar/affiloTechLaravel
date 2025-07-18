<?php

namespace App\Plugins\Awin;

use App\Helper\Static\Vars;
use App\Helper\Static\Methods;
use App\Models\Advertiser as AdvertiserModel;
use App\Models\Commission;
use App\Models\ValidationDomain;

class AdvertiserDetail extends Base
{

    public function callApi($data)
    {
        Methods::customAwin("AWIN ADVERTISER DETAIL", "START AWIN ADVERTISER ID: {$data['advertiser_id']}");
        if(isset($data['advertiser_id'])) {

            $response = $this->sendAwinAdvertiserDetailRequest($data);
            $this->storeData($response);

        }
        Methods::customAwin("AWIN ADVERTISER DETAIL", "END AWIN ADVERTISER ID: {$data['advertiser_id']}");
    }

    public function storeData($response)
    {
        $vars = $this->getAwinAdvertiserDetailStaticVar();
        $source = $vars['source'];
        $createdBy = $vars['created_by'];

        $kpi = $response['kpi'] ?? [];
        $commissions = $response['commissionRange'] ?? [];
        $response = $response['programmeInfo'] ?? [];

        if(isset($response['id'])) {

            $advertiser = AdvertiserModel::where([
                'advertiser_id'   =>    $response['id'],
                'source'          =>    $source
            ])->first();

            if($advertiser)
            {

                $advertiser->update([
                    "advertiser_id" => $response['id'],
                    "average_payment_time" => $kpi['averagePaymentTime'] ?? null,
                    "valid_domains" => is_array($response['validDomains']) ? array_column($response['validDomains'], 'domain') : [$response['validDomains']],
                    "validation_days" => $kpi['validationDays'] ?? null,
                    "epc" => $kpi['epc'] ?? null,
                    "deeplink_enabled" => $response['deeplinkEnabled'],
                ]);

                if(isset($response['validDomains']) && $response['validDomains'])
                {

                    foreach (array_column($response['validDomains'], 'domain') as $validation)
                    {
                        if($validation)
                        {
                            ValidationDomain::updateOrCreate(
                                [
                                    'name' => $validation,
                                    "advertiser_id" => $advertiser->id
                                ],
                                [
                                    "created_by" => $createdBy
                                ]
                            );
                        }
                    }

                }

                $this->storeCommission($advertiser, $commissions);

            }

        }

        $this->changeJobTime();
    }

    private function storeCommission($advertiser, $commissions)
    {
        $vars = $this->getAwinAdvertiserDetailStaticVar();
        $createdBy = $vars['created_by'];
        $commissionLinkscircle = $vars['commission_linkscircle'];
        $defaultCreated = $vars['default_created'];

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

            sleep(2);
        }

        foreach ($commissions as $commission)
        {
            $rate = null;

            if(str_contains($commission['max'], "-")) {
                $rate = explode("-", $commission['max']);
            } else {
                $rate = floatval($commission['max']);
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
                "type" => $commission['type'],
                "info"            => "Network Rate"
            ]);

            if($advertiser->rate <= $rate) {

                $advertiser->update([
                    "commission" => $rate,
                    "commission_type" => $commission['type'] == "percentage" ? "%" : $advertiser->currency_code
                ]);

            }

        }
    }
}
