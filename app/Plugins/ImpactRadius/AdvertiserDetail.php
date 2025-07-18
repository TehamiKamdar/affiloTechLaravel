<?php

namespace App\Plugins\ImpactRadius;

use App\Helper\Static\Vars;
use App\Helper\Static\Methods;
use App\Models\Advertiser as AdvertiserModel;
use App\Models\Commission;
use App\Models\ValidationDomain;

class AdvertiserDetail extends Base
{

    public function storeData($id)
    {
        $advertiser = AdvertiserModel::where([
            'advertiser_id'   =>    $id,
            'source'          =>    Vars::IMPACT_RADIUS
        ])->first();

        if($advertiser)
            $this->storeCommission($advertiser);
    }

    private function storeCommission($advertiser)
    {
        $commission = $this->sendImpactRadiusAdvertiserDetailRequest($advertiser->advertiser_id);
        $commission = $commission['PayoutTermsList'][0] ?? [];

        $commissionCheck = Commission::where('advertiser_id', $advertiser->id)->count();
        if($commissionCheck == 0)
        {
            $rate = Vars::COMMISSION_LINKSCIRCLE;
            $type = "percentage";
            Commission::create([
                'advertiser_id'   => $advertiser->id,
                "created_by"      => Vars::DEFAULT_GENERATED,
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

        $rate = floatval($commission['PayoutPercentage'] ?? 0);

        Commission::updateOrCreate([
            'advertiser_id'   => $advertiser->id,
            "created_by"      => Vars::CRON_JOB_CREATED
        ],[
            "rate" => $rate,
            "condition" => $commission['TrackerName'] ?? null,
            "type" => "percentage",
            "info" => "Network Rate"
        ]);

        $advertiser->update([
            "validation_days" => $commission['ReferralPeriod'] ?? null,
            "commission" => $rate,
            "commission_type" => "%"
        ]);
    }
}
