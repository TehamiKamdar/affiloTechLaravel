<?php

namespace App\Plugins\ImpactRadius;

use App\Helper\PublisherData;
use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser as AdvertiserModel;
use App\Models\FetchDailyData;
use App\Traits\Main;

class Advertiser extends Base
{
    use Main;

    protected $advertiserData;

    public function callApi($param)
    {
        $advertisersData = $this->sendImpactRadiusAdvertiserRequest($param['page']);
        $this->storeData($advertisersData["Campaigns"] ?? []);
    }

    public function storeData($advertiserData)
    {
        $vars = $this->getImpactAdvertiserStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];
        $available = $vars['available'];
        $type = $vars['type'];

        $this->advertiserData = $advertiserData;
        foreach ($advertiserData as $data)
        {
            if(isset($data['CampaignId'])) {

                $advertiser = AdvertiserModel::updateOrCreate([
                    'advertiser_id'   => $data['CampaignId'],
                    'source' => $source
                ],[
                    "name" => $data['CampaignName'],
                    "url" => $data['CampaignUrl'],
                    "click_through_url" => $data['TrackingLink'],
                    "short_description" => $data['CampaignDescription'],
                    "deeplink_enabled" => $data['AllowsDeeplinking'] == "true" ? 1 : 0,
                    "type" => $type,
                    "is_available" => $available
                ]);

                if($advertiser->wasRecentlyCreated)
                {
                    $advertiser->update([
                        "promotional_methods" => PublisherData::getMixIdz(["Coupon Site", "Blog Site", "Content Site", "Social Media", "Email Marketing"]),
                        "program_restrictions" => PublisherData::getMixIdz(["PPC Site", "TM+ Bidding"])
                    ]);
                }

                $queue = Vars::IMPACT_RADIUS_ON_QUEUE;
                $detailType = Vars::ADVERTISER_DETAIL;
                $processDateFormat = Vars::CUSTOM_DATE_FORMAT_3;
                $dateFormat = Vars::CUSTOM_DATE_FORMAT_2;

                FetchDailyData::updateOrCreate([
                    "path" => "ImpactAdvertiserDetailJob",
                    "process_date" => now()->format($processDateFormat),
                    "payload" => json_encode(["id" => $data['CampaignId']]),
                    "queue" => $queue,
                    "source" => $source,
                    "type" => $detailType
                ], [
                    "name" => "Impact Advertiser Detail Job",
                    "date" => now()->format($dateFormat),
                    "sort" => $this->setSortingFetchDailyData($source),
                ]);

                FetchDailyData::updateOrCreate([
                    "path" => "ImpactAdvertiserImageUploadJob",
                    "process_date" => now()->format($processDateFormat),
                    "key" => $data['CampaignId'],
                    "queue" => $queue,
                    "source" => $source,
                    "type" => $detailType
                ], [
                    "name" => "Impact Advertiser Image Upload Job",
                    "date" => now()->format($dateFormat),
                    "payload" => json_encode(['id' => $data['CampaignId']]),
                    "sort" => $this->setSortingFetchDailyData($source),
                ]);

            }
        }
    }

}
