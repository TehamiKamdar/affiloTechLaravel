<?php

namespace App\Plugins\Traderdoubler;

use App\Helper\PublisherData;
use App\Helper\Static\Vars;
use App\Helper\Static\Methods;
use App\Models\Advertiser as AdvertiserModel;
use App\Models\Country;
use App\Models\FetchDailyData;
use App\Models\Mix;
use App\Traits\Main;

class Advertiser extends Base
{
    use Main;

    public function callApi($param)
    {

        $advertisersData = $this->sendTradedoublerAdvertiserRequest($param['source_id'], $param['offset']);
        if(isset($advertisersData["items"]))
        {
            foreach ($advertisersData["items"] as $advertiser)
            {
                $advertiser = array_merge($advertiser, ['source' => $param['source_id']]);
                $this->storeData($advertiser);
            }
        }

    }

    public function storeData($data)
    {
        $vars = $this->getTradedoublerAdvertiserStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];
        $available = $vars['available'];
        $type = $vars['type'];

        if(isset($data['id'])) {

//            Methods::customTradedoubler($module, "ADVERTISER ID: {$data['id']} FETCHING START");

            $country = Country::where("iso2", $data['countryCode'])->first();

            $categpries = $this->prepareCategories($data['categoryIds']);

            $advertiserData = [
                "name" => $data['name'],
                "url" => $data['homePage'],
                "primary_regions" => [$data['countryCode']],
                "currency_code" => $data['currencyCode'],
                "deeplink_enabled" => $data['deepLinking'] ?? 0,
                "epc" => $data['avgEpc'] ?? 0,
                "average_payment_time" => $data['avgPaymentDays'],
                "categories" => $categpries,
                "fetch_logo_url" => isset($data['logoUrl']) && $this->logoURLCheck($data['logoUrl']) ? $data['logoUrl'] : null,
                "click_through_url" => "https://clk.tradedoubler.com/click?p={$data['id']}&a={$data['source']}",
                "type" => $type,
                "is_available" => $available,
                "network_source_id" => $data['source']
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
                    "program_restrictions" => PublisherData::getMixIdz(["PPC Site", "TM+ Bidding"])
                ]);
            }

            $queue = Vars::AWIN_ON_QUEUE;
            $detailType = Vars::ADVERTISER_DETAIL;
            $processDateFormat = Vars::CUSTOM_DATE_FORMAT_3;
            $dateFormat = Vars::CUSTOM_DATE_FORMAT_2;

            FetchDailyData::updateOrCreate([
                "path" => "TradedoublerAdvertiserDetailJob",
                "process_date" => now()->format($processDateFormat),
                "key" => $data['id'],
                "queue" => $queue,
                "source" => $source,
                "type" => $detailType
            ], [
                "name" => "Tradedoubler Advertiser Detail Job",
                "payload" => json_encode(['id' => $data['id']]),
                "date" => now()->format($dateFormat),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);

//                Methods::customTradedoubler(null, "ADVERTISER ID: {$data['id']} FETCHING END");

        }
    }

    private function prepareCategories($idz)
    {
        $vars = $this->getTradedoublerAdvertiserStaticVar();
        $source = $vars['source'];
        return Mix::select('id')->whereIn('external_id', $idz)->where('source', $source)->get()->pluck("id");
    }
}
