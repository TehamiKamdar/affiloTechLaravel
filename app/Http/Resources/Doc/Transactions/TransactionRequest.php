<?php

namespace App\Http\Resources\Doc\Transactions;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionRequest extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "transaction_id" => $this->transaction_id,
            "advertiser_id" => $this->advertiser_id,
            "internal_advertiser_id" => $this->internal_advertiser_id,
            "website_id" => $this->website_id,
            "publisher_id" => $this->publisher_id,
            "payment_id" => $this->payment_id,
            "commission_status" => $this->commission_status,
            "payment_status" => $this->payment_status,
            "commission_amount" => $this->commission_amount,
            "commission_amount_currency" => $this->commission_amount_currency,
            "sale_amount" => $this->sale_amount,
            "sale_amount_currency" => $this->sale_amount_currency,
            "advertiser_name" => $this->advertiser_name,
            "transaction_date" => $this->transaction_date,
            "commission_type" => $this->commission_type,
            "url" => $this->url
        ];
    }
}
