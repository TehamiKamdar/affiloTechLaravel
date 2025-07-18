<?php

namespace App\Http\Resources\Doc\Advertisers;

use App\Helper\PublisherData;
use App\Helper\Static\Methods;
use App\Models\AdvertiserPublisher;
use Illuminate\Http\Resources\Json\JsonResource;

class AdvertiserResource extends JsonResource{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $advertiserApply = $this;
        $advertiser = $advertiserApply->advertiser ?? null;
        if(empty($advertiser->sid ?? null) || (!isset($advertiserApply->advertiser) && !$advertiserApply->advertiser))
            return [];

        $categories = isset($advertiser->categories) && !empty($advertiser->categories) ? PublisherData::getMixNames($advertiser->categories) : null;
        $promotional_methods = isset($advertiser->promotional_methods) && !empty($advertiser->promotional_methods) ? PublisherData::getMixNames($advertiser->promotional_methods) : null;
        $program_restrictions = isset($advertiser->program_restrictions) && !empty($advertiser->program_restrictions) ? PublisherData::getMixNames($advertiser->program_restrictions) : null;
        return [
            'id' => $advertiser->sid,
            'name' => $advertiser->name,
            'url' => $advertiser->url,
            'logo' => Methods::staticMainAsset($advertiser->logo),
            'primary_regions' => isset($advertiser->primary_regions) && !empty($advertiser->primary_regions) ? $advertiser->primary_regions : [],
            'supported_regions' => isset($advertiser->supported_regions) && !empty($advertiser->supported_regions) ? $advertiser->supported_regions : [],
            'currency_code' => $advertiser->currency_code,
            'average_payment_time' => $advertiser->average_payment_time,
            'epc' => $advertiser->epc,
            'click_through_url' => $advertiserApply->status == AdvertiserPublisher::STATUS_ACTIVE ? $advertiserApply->tracking_url : null,
            'click_through_short_url' => $advertiserApply->status == AdvertiserPublisher::STATUS_ACTIVE ? $advertiserApply->tracking_url_short : null,
            'validation_days' => $advertiser->validation_days,
            'status' => $advertiserApply->status,
            'commission' => "{$advertiser->commission}{$advertiser->commission_type}",
            'goto_cookie_lifetime' => $advertiser->goto_cookie_lifetime,
            'exclusive' => $advertiser->exclusive,
            'deeplink_enabled' => $advertiser->deeplink_enabled,
            'categories' => $categories,
            'program_restrictions' => $program_restrictions,
            'promotional_methods' => $promotional_methods,
            'description' => $advertiser->description,
            'short_description' => $advertiser->short_description,
            'program_policies' => $advertiser->program_policies,
        ];
    }
}
