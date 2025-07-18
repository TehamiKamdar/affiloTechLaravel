<?php

namespace App\Http\Resources\Doc\Websites;

use App\Helper\PublisherData;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class WebsiteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {

        if(!isset($this->status))
            return [];

        $status = ucwords($this->status);

        $partnerTypes = PublisherData::getMixNames($this->partner_types);
        $categories = PublisherData::getMixNames($this->categories);

        return [
            'id' => $this->wid,
            'name' => $this->name,
            'url' => $this->url,
            'partner_types' => $partnerTypes,
            'categories' => $categories,
            'status' => $status,
            'monthly_traffic' => $this->monthly_traffic,
            'monthly_page_views' => $this->monthly_page_views,
            'last_updated' => Carbon::parse($this->updated_at)->format("m/d/Y"),
        ];
    }
}
