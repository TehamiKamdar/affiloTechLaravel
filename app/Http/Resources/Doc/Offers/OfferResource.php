<?php

namespace App\Http\Resources\Doc\Offers;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if(!isset($this->id))
            return [];

        return [
            'id' => $this->id,
            'name' => $this->title,
            'advertiser_id' => $this->sid,
            'advertiser_name' => $this->advertiser_name,
            'advertiser_url' => $this->url,
            'url_tracking' => route("track.coupon", ['advertiser' => $this->internal_advertiser_id, 'website' => $this->advertiser_applies_without_auth->website_id, 'coupon' => $this->id]),
            'advertiser_status' => $this->advertiser_status,
            'type' => $this->type,
            'description' => $this->description,
            'terms' => $this->terms,
            'start_date' => Carbon::parse($this->start_date)->format("d/m/Y"),
            'end_date' => Carbon::parse($this->end_date)->format("d/m/Y"),
            'code' => $this->code ? $this->code : "No code required",
            'exclusive' => $this->exclusive,
            'regions' => $this->regions,
            'categories' => $this->categories
        ];
    }
}
