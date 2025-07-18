<?php

namespace App\Http\Resources\Doc\Transactions;

use App\Models\Transaction;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $status = null;
        if ($this->payment_status == Transaction::PAYMENT_STATUS_RELEASE_PAYMENT || $this->payment_status == Transaction::PAYMENT_STATUS_CONFIRM) {
            $status = Transaction::STATUS_PAID;
        } elseif ($this->payment_status == Transaction::PAYMENT_STATUS_RELEASE) {
            $status = ucwords(str_replace('_', ' ', Transaction::STATUS_PENDING_PAID));
        } elseif ($this->commission_status == Transaction::STATUS_PENDING) {
            $status = Transaction::STATUS_PENDING;
        } elseif ($this->commission_status == Transaction::STATUS_HOLD) {
            $status = Transaction::STATUS_HOLD;
        } elseif ($this->commission_status == Transaction::STATUS_APPROVED) {
            $status = Transaction::STATUS_APPROVED;
        } elseif ($this->commission_status == Transaction::STATUS_PAID) {
            $status = Transaction::STATUS_PAID;
        } elseif ($this->commission_status == Transaction::STATUS_DECLINED) {
            $status = Transaction::STATUS_DECLINED;
        }

        return [
            "transaction_id" => $this->transaction_id,
            "advertiser_id" => $this->advertiser->sid ?? null,
            "advertiser_name" => $this->advertiser_name,
            "publisher_id" => $this->publisher->sid ?? null,
            "publisher_name" => $this->publisher->first_name . " " . $this->publisher->last_name,
            "website_id" => $this->website->wid,
            "website_name" => $this->website->name,
            "payment_id" => $this->internal_payment_id ? $this->internal_payment_id : null,
            "commission_status" => ucwords($status),
            "commission_amount" => $this->commission_amount,
            "commission_amount_currency" => $this->commission_amount_currency,
            "sale_amount" => $this->sale_amount,
            "sale_amount_currency" => $this->sale_amount_currency,
            "transaction_date" => $this->transaction_date,
            "commission_type" => $this->commission_type,
            "url" => $this->url,
            "sub_id" => $this->sub_id ?? null
        ];
    }
}
