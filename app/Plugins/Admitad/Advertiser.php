<?php

namespace App\Plugins\Admitad;

use App\Helper\Static\Methods;
use App\Models\Advertiser as AdvertiserModel;
use App\Models\Commission;
use App\Models\Country;
use App\Models\Mix;

class Advertiser extends Base
{
    public function callApi($param)
    {
        $advertisersData = $this->sendAdmitadAdvertiserRequest($param['website_id'], $param['offset']);

        if(isset($advertisersData['results']) && count($advertisersData['results']))
        {
            foreach($advertisersData['results'] as $data) {
                $advertiser = new Advertiser();
                $advertiser->storeData($data);
            }
        }
    }

    public function storeData($data)
    {
        $vars = $this->getAdmitadAdvertiserStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];
        $available = $vars['available'];
        $type = $vars['type'];

        if(isset($data['id'])) {

            if(isset($data['traffics'])) {
                $program_restrictions = $promotional_methods = [];
                foreach ($data['traffics'] as $traffic) {
                    $trafficName = Mix::select('id')->where("source", $source)->where('external_id', $traffic['id'])->where('type', Mix::PROMOTIONAL_METHOD)->first();
                    if($trafficName)
                    {
                        if($traffic['enabled']) {
                            $promotional_methods[] = $trafficName->id;
                        } else {
                            $program_restrictions[] = $trafficName->id;
                        }
                    }
                }
            }

            $categoryIDz = [];
            foreach ($data['categories'] as $category)
            {
                $categoryIDz[] = $category['id'];
                isset($category['parent']['id']) ? $categoryIDz[] = $category['parent']['id'] : null;
            }

            $categories = Mix::select('id')->where("source", $source)->whereIn('external_id', $categoryIDz)->where('type', Mix::CATEGORY)->get()->pluck('id')->toArray();

            $regions = array_column($data['regions'], "region") ?? null;

            $countries = Country::whereIn("iso2", $regions)->get()->pluck('name')->toArray();

            $name = $data['name'];
            if(empty($name))
            {
                $name = explode(',', $data['name_aliases']);
                $name = $name[0];
            }

            $img = $this->uploadImage($data['image'], $name);

            $advertiserData = [
                "name" => $name,
                "url" => $data['site_url'],
                "logo" => $img,
                "currency_code" => $data['currency'],
                "deeplink_enabled" => $data['allow_deeplink'],
                "average_payment_time" => $data['avg_money_transfer_time'],
                "epc" => $data['epc'] ?? null,
                "description" => $data['description'],
                "primary_regions" => $regions,
                "country_full_name" => $countries ?? null,
                "goto_cookie_lifetime" => $data['goto_cookie_lifetime'] ?? null,
                "exclusive" => $data['exclusive'] ?? false,
                "categories" => $categories ?? null,
                "promotional_methods" => $promotional_methods ?? null,
                "program_restrictions" => $program_restrictions ?? null,
                "click_through_url" => $data['gotolink'],
                "source" => $source,
                "type" => $type,
                "is_available" => $available
            ];

            $advertiser = AdvertiserModel::updateOrCreate([
                'advertiser_id'   => $data['id'],
                'source' => $source
            ],$advertiserData);

            $this->storeCommission($data, $advertiser);

        }
    }

    private function storeCommission($data, $advertiser)
    {
        $vars = $this->getAdmitadAdvertiserStaticVar();
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

        }

        foreach ($data['actions'] as $commission)
        {
            $rate = null;
            if(str_contains($commission['payment_size'], "-")) {
                $rate = explode("-", $commission['payment_size']);
            } else {
                $rate = floatval($commission['payment_size']);
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
                "condition" => $commission['name'],
                "type" => str_contains($commission['payment_size'], "%") ? "percentage" : "amount"
            ]);

            if($advertiser->rate <= $rate) {

                $numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
                $type = str_replace($numbers, "", $commission['payment_size']);
                $type = preg_replace(
                    '/[^a-zA-Z0-9+%?]/m', // 1. regex to apply
                    '',                 // 2. replacement for regex matches
                    $type             // 3. the original string
                );

                $advertiser->update([
                    "commission" => $rate,
                    "commission_type" => $type
                ]);

            }
        }
    }

}
