<?php

namespace App\Plugins\Rakuten;

use App\Helper\PublisherData;
use App\Helper\Static\Vars;
use App\Helper\Static\Methods;
use App\Models\Advertiser as AdvertiserModel;
use App\Models\FetchDailyData;
use App\Traits\Main;

class Advertiser extends Base
{
    use Main;

    public function callApi($param)
    {
        $advertisersData = $this->sendRakutenAdvertiserRequest($param['page']);
        foreach ($advertisersData["advertisers"] ?? [] as $data)
        {
            $this->storeData($data);
        }
    }

    public function storeData($data)
    {
        $vars = $this->getRakutenAdvertiserStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];
        $available = $vars['available'];
        $type = $vars['type'];

        if(isset($data['id']) && Methods::isEnglish($data['name'])) {

            $advertiser = AdvertiserModel::updateOrCreate([
                'advertiser_id'   => $data['id'],
                'source' => $source
            ],[
                "name" => $data['name'],
                "url" => $data['url'],
                "short_description" => $data['description'] ?? null,
                "deeplink_enabled" => $data['features']['deep_links'] ?? 0,
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

            $queue = Vars::RAKUTEN_ON_QUEUE;
            $detailType = Vars::ADVERTISER_DETAIL;
            $processDateFormat = Vars::CUSTOM_DATE_FORMAT_3;
            $dateFormat = Vars::CUSTOM_DATE_FORMAT_2;

            FetchDailyData::updateOrCreate([
                "path" => "RakutenReCheckIsAvailableJob",
                "process_date" => now()->format($processDateFormat),
                "key" => $data['id'],
                "queue" => $queue,
                "source" => $source,
                "type" => $detailType
            ], [
                "name" => "Re Check Is Available Job",
                "payload" => json_encode(['id' => $data['id']]),
                "date" => now()->format($dateFormat),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);

            FetchDailyData::updateOrCreate([
                "path" => "RakutenAdvertiserRelatedInfoJob",
                "process_date" => now()->format($processDateFormat),
                "key" => $data['id'],
                "queue" => $queue,
                "source" => $source,
                "type" => $detailType
            ], [
                "name" => "Advertiser Related Info Job",
                "payload" => json_encode(['id' => $data['id']]),
                "date" => now()->format($dateFormat),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);

        }
    }

}
