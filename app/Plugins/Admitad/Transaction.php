<?php

namespace App\Plugins\Admitad;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\Tracking;
use App\Models\Transaction as TransactionModel;
use App\Models\User;
use App\Models\Website;
use Carbon\Carbon;

class Transaction extends Base
{
    public function callApi($data)
    {
        $transactionsData = $this->sendAdmitadTransactionRequest($data['offset'], $data['start']);
        $transactionsData = is_array($transactionsData) ? $transactionsData : @json_decode($transactionsData, true);
        if(isset($transactionsData['results']) && count($transactionsData['results']))
        {
            foreach($transactionsData['results'] as $transactions) {
                $this->storeTransaction($transactions);
            }
        }
        $this->changeJobTime();
    }

    private function storeTransaction($response)
    {
        $advertiser = Advertiser::where("advertiser_id", $response['advcampaign_id'])->where('source', Vars::ADMITAD)->first();

        if($advertiser)
        {

            Methods::customAdmitad("TRANSACTION JOB", "ADVERTISER ID {$response['advcampaign_id']} & TRANSACTION ID: {$response['action_id']} FETCHING START");


            $clickDate = $response['click_date'] ?? null;
            $transactionDate = $response['action_date'] ?? null;
            $validationDate = null;

            $publisherID = $websiteID = $subID = null;
            if($response['subid'] && $response['subid1'] && $response['subid2'] && $response['subid3'])
            {
                $publisherID = $response['subid1'];
                $websiteID = $response['subid2'];
                $subID = $response['subid3'];
            }
            elseif($response['subid'] && $response['subid1'] && $response['subid2'] && empty($response['subid3']))
            {
                $publisherID = $response['subid1'];
                $websiteID = $response['subid2'];
                $subID = null;
            }
            elseif($response['subid'] && empty($response['subid1']) && empty($response['subid2']) && empty($response['subid3']))
            {
                $tracking = Tracking::select(['id', 'publisher_id', 'website_id', 'sub_id'])->where("advertiser_id", $advertiser->id)->where("sub_id", $response['subid'])->first();
                $websiteID = $tracking->website_id;
                $publisherID = $tracking->publisher_id;
                $subID = $tracking->sub_id;
            }
            elseif(empty($response['subid']) && empty($response['subid1']) && empty($response['subid2']) && empty($response['subid3']))
            {
                $website = Website::select('id')->where(function($query) use ($response) {
                    $name = $response['website_name'];
                    $query->orWhere("url", $response['click_user_referer']);
                    $query->orWhere("name", "LIKE", "%$name%");
                })->first();

                if($website)
                {
                    $tracking = Tracking::select(['id', 'publisher_id', 'website_id', 'sub_id'])->where("advertiser_id", $advertiser->id)->where("website_id", $website->id)->first();
                    $websiteID = $tracking->website_id;
                    $publisherID = $tracking->publisher_id;
                    $subID = $tracking->sub_id;
                }

            }

            $transaction = TransactionModel::where([
                'transaction_id'                                    =>       $response['action_id'],
                'internal_advertiser_id'                            =>       $advertiser->id
            ])->first();

            if(empty($websiteID) && empty($publisherID) && $transaction)
            {
                $websiteID = $transaction->website_id;
                $publisherID = $transaction->publisher_id;
            }

            $data = [
                'internal_advertiser_id'                            =>      $advertiser->id,
                'external_advertiser_id'                            =>      $advertiser->sid,
                'advertiser_id'                                     =>      $response['advcampaign_id'],
                'commission_status'                                 =>      $response['status'],
                'publisher_id'                                      =>      $publisherID,
                'website_id'                                        =>      $websiteID,
                'sub_id'                                            =>      $subID,
                'advertiser_name'                                   =>      $advertiser->name ?? null,
                'site_name'                                         =>      $response['website_name'] ?? null,
                'customer_country'                                  =>      $response['click_country_code'] ?? null,
                'click_date'                                        =>      $clickDate,
                'transaction_date'                                  =>      $transactionDate,
                'validation_date'                                   =>      $validationDate,
                'voucher_code'                                      =>      $response['promocode'] ?? null,
                'click_device'                                      =>      $response['clickDevice'] ?? null,
                'ip_hash'                                           =>      $response['click_user_ip'] ?? null,
                'publisher_url'                                     =>      $response['click_user_referer'] ?? null,
                "source"                                            =>      $advertiser->source,
                'commission_type'                                   =>      $response['action'] ?? null,
                'order_ref'                                         =>      $response['order_id'] ?? null,
                'amended_reason'                                    =>      $response['comment'] ?? null,
            ];

            if(isset($transaction->paid_to_publisher) && $transaction->paid_to_publisher == TransactionModel::PAYMENT_STATUS_CONFIRM)
            {
                unset($data['commission_status']);
            }

            $received_sale_amount = abs($response['cart']);
            $received_commission_amount = abs($response['payment']);

            if(empty($transaction) || $transaction->is_converted == 0 || ($transaction->received_commission_amount != $received_commission_amount) || ($transaction->commission_amount = 0 || $transaction->commission_amount < 0 || $transaction->commission_amount == null || $transaction->commission_amount == "") && $received_commission_amount < 0) {
                $received_commission_amount_currency = $response['currency'];
                if($received_commission_amount_currency == "USD")
                {
                    $commission_amount = $received_commission_amount;
                    $commission_amount_currency = $received_commission_amount_currency;
                }
                else
                {
                    $amount = Methods::parseAmountToUSD($received_commission_amount_currency, $received_commission_amount, $transactionDate);
                    $commission_amount = $amount;
                    $commission_amount_currency = "USD";
                }

                $received_sale_amount_currency = $response['currency'];
                if($received_sale_amount_currency == "USD")
                {
                    $sale_amount = $received_sale_amount;
                    $sale_amount_currency = $received_sale_amount_currency;
                }
                else
                {
                    $amount = Methods::parseAmountToUSD($received_sale_amount_currency, $received_sale_amount, $transactionDate);
                    $sale_amount = $amount;
                    $sale_amount_currency = "USD";;
                }

                $data['commission_amount'] = $commission_amount;
                $data['commission_amount_currency'] = $commission_amount_currency;
                $data['sale_amount'] = $sale_amount;
                $data['sale_amount_currency'] = $sale_amount_currency;
                $data['received_commission_amount'] = $received_commission_amount;
                $data['received_commission_amount_currency'] = $received_commission_amount_currency;
                $data['received_sale_amount'] = $received_sale_amount;
                $data['received_sale_amount_currency'] = $received_sale_amount_currency;

                if($received_sale_amount == 0)
                    $data['is_converted'] = 1;

                elseif ($received_commission_amount > 0)
                    $data['is_converted'] = 1;

            }

            TransactionModel::updateOrCreate([
                'transaction_id'                                    =>       $response['action_id'],
                'internal_advertiser_id'                            =>       $advertiser->id
            ], $data);

            Methods::customAdmitad(null, "ADVERTISER ID {$response['advcampaign_id']} & TRANSACTION ID: {$response['action_id']} FETCHING END");

        }
    }
}
