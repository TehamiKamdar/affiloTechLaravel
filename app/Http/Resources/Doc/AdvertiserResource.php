<?php

namespace App\Http\Resources\Doc;

use App\Helper\PublisherData;
use App\Helper\Static\Methods;
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
        $categories = isset($this->categories) && !empty($this->categories) ? PublisherData::getMixNames($this->categories) : null;
        $promotional_methods = isset($this->promotional_methods) && !empty($this->promotional_methods) ? PublisherData::getMixNames($this->promotional_methods) : null;
        $program_restrictions = isset($this->program_restrictions) && !empty($this->program_restrictions) ? PublisherData::getMixNames($this->program_restrictions) : null;
        return [
            'id' => $this->sid,
            'name' => $this->name,
            'url' => $this->url,
            'logo' => Methods::staticMainAsset($this->logo),
            'primary_regions' => isset($this->primary_regions) && !empty($this->primary_regions) ? $this->primary_regions : [],
            'supported_regions' => isset($this->supported_regions) && !empty($this->supported_regions) ? $this->supported_regions : [],
            'currency_code' => $this->currency_code,
            'average_payment_time' => $this->average_payment_time,
            'epc' => $this->epc,
            'click_through_url' => $this->advertiser_applies_without_auth->tracking_url ?? null,
            'click_through_short_url' => $this->advertiser_applies_without_auth->tracking_url_short ?? null,
            'validation_days' => $this->validation_days,
            'status' => ucwords(str_replace("_", " ", $this->advertiser_applies_without_auth->status ?? "not_joined")),
            'commission' => "{$this->commission}{$this->commission_type}",
            'goto_cookie_lifetime' => $this->goto_cookie_lifetime,
            'exclusive' => $this->exclusive,
            'deeplink_enabled' => $this->deeplink_enabled,
            'categories' => $categories,
            'program_restrictions' => $program_restrictions,
            'promotional_methods' => $promotional_methods,
            'description' => $this->description,
            'short_description' => $this->short_description,
            'program_policies' => $this->program_policies,
        ];
    }
}
