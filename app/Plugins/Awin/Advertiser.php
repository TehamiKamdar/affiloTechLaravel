<?php

namespace App\Plugins\Awin;

use App\Helper\PublisherData;
use App\Helper\Static\Vars;
use App\Models\Advertiser as AdvertiserModel;
use App\Models\Country;
use App\Models\FetchDailyData;
use App\Traits\Main;

class Advertiser extends Base
{
    use Main;

    protected $advertiserData;

    public function callApi()
    {
        $advertisersData = $this->sendAwinAdvertiserRequest();

        foreach (array_chunk($advertisersData, Vars::AWIN_ADVERTISER_LIMIT) as $data)
        {
            $advertiser = new Advertiser();
            $advertiser->storeData($data);
        }
    }

    public function storeData($advertiserData)
    {
        $vars = $this->getAwinAdvertiserStaticVar();
        $source = $vars['source'];
        $available = $vars['available'];
        $type = $vars['type'];

        $this->advertiserData = $advertiserData;

        foreach ($advertiserData as $data)
        {
            if(isset($data['id'])) {

                $country = Country::where("iso2", $data['primaryRegion']['countryCode'])->first();

                $advertiserData = [
                    "advertiser_id" => $data['id'],
                    "name" => $data['name'],
                    "url" => $data['displayUrl'],
                    "primary_regions" => [$data['primaryRegion']['countryCode']],
                    "currency_code" => $data['currencyCode'],
                    "click_through_url" => $data['clickThroughUrl'],
                    "short_description" => $data['description'],
                    "source" => $source,
                    "type" => $type,
                    "is_available" => $available,
                ];

                if(isset($country->name))
                    $advertiserData["country_full_name"] = [$country->name];

                $advertiser = AdvertiserModel::updateOrCreate([
                    'advertiser_id'   => $data['id'],
                    'source' => $source
                ], $advertiserData);

                if($advertiser->wasRecentlyCreated)
                {
                    $advertiser->update([
                        "promotional_methods" => PublisherData::getMixIdz(["Coupon Site", "Blog Site", "Content Site", "Social Media", "Email Marketing"]),
                        "program_restrictions" => PublisherData::getMixIdz(["PPC Site", "TM+ Bidding"]),
                        "fetch_logo_url" => empty($advertiser->logo) && $data['logoUrl'] ? $data['logoUrl'] : null
                    ]);
                }
                else
                {
                    $advertiser->update([
                        "fetch_logo_url" => empty($advertiser->logo) && $data['logoUrl'] ? $data['logoUrl'] : null
                    ]);
                }

                $queue = Vars::AWIN_ON_QUEUE;
                $detailType = Vars::ADVERTISER_DETAIL;
                $processDateFormat = Vars::CUSTOM_DATE_FORMAT_3;
                $dateFormat = Vars::CUSTOM_DATE_FORMAT_2;

                FetchDailyData::updateOrCreate([
                    "path" => "AwinAdvertiserDetailJob",
                    "process_date" => now()->format($processDateFormat),
                    "key" => $advertiserData['advertiser_id'],
                    "queue" => $queue,
                    "source" => $source,
                    "type" => $detailType
                ], [
                    "name" => "Awin Advertiser Detail Job",
                    "payload" => json_encode(['id' => $advertiserData['advertiser_id']]),
                    "date" => now()->format($dateFormat),
                    "sort" => $this->setSortingFetchDailyData($source),
                ]);

                if(isset($advertiser->fetch_logo_url) && $advertiser->fetch_logo_url)
                {
                    FetchDailyData::updateOrCreate([
                        "path" => "AwinAdvertiserImageUploadFetchJob",
                        "process_date" => now()->format($processDateFormat),
                        "key" => $advertiser->advertiser_id,
                        "queue" => $queue,
                        "source" => $source,
                        "type" => $detailType
                    ], [
                        "name" => "Awin Advertiser Image Upload Fetch Job",
                        "payload" => json_encode($advertiser->only(["fetch_logo_url", "name", "advertiser_id"])),
                        "date" => now()->format($dateFormat),
                        "sort" => $this->setSortingFetchDailyData($source),
                    ]);
                }

            }
        }
    }

}
