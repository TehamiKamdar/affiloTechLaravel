<?php

namespace App\Plugins\ImpactRadius;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\Transaction as TransactionModel;
use App\Models\Website;
use App\Traits\JobTrait;
use App\Traits\RequestTrait;
use Carbon\Carbon;

class Transaction extends Base
{

    public function callApi($data): void
    {
        $vars = $this->getImpactTransactionStaticVar();
        $module = $vars['module_name'];

        $page = 1;
        $loop2 = true;
        while ($loop2)
        {
            $transactionsData = $this->sendImpactTransactionRequest($data['start'], $data['end'], $page);

            if(isset($transactionsData['@numpages']) && $page <= $transactionsData['@numpages'])
            {
                $this->makeQueueableImpactRadiusTransactionJob($transactionsData);
                $page++;
            } else {
                $page = 1;
                $loop2 = false;
            }
        }

    }

    private function makeQueueableImpactRadiusTransactionJob($transactionsData): void
    {
        $vars = $this->getImpactTransactionStaticVar();
        $limit = $vars['limit'];

        foreach (array_chunk($transactionsData['Actions'] ?? [], $limit) as $transactionChunk)
        {
            foreach ($transactionChunk as $response)
            {
                if(isset($response['SubId1']) && isset($response['SubId2']) || isset($response['ReferringDomain']))
                {
                    $this->storeTransaction($response);
                }
            }
            $this->changeJobTime();
        }
    }

    private function storeTransaction($response): void
    {
        $vars = $this->getImpactTransactionStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];

        $id = $response['Id'] ?? 0;

        if($id)
        {
//                Methods::customImpactRadius($module, "ADVERTISER ID: {$response['CampaignId']} & TRANSACTION ID: {$id} FETCHING START");

            $advertiser = Advertiser::where("advertiser_id", $response['CampaignId'])->where('source', $source)->first();

            $transactionDate = $response['EventDate'] ?? null;
            if($transactionDate)
                $transactionDate = Carbon::parse(str_replace("T", "  ", $transactionDate))->format("Y-m-d H:i:s");

            if($advertiser)
            {
                $validationDate = $clickDate = $publisherID = $websiteID = null;
                if(isset($response['SubId1']) && isset($response['SubId2']))
                {
                    $publisherID = $response['SubId1'];
                    $websiteID = $response['SubId2'];
                } elseif (isset($response['ReferringDomain']))
                {
                    $url = $response['ReferringDomain'];
                    $url = parse_url($url, PHP_URL_HOST);
                    $website = Website::with('users:id')->where('url', 'LIKE', "%$url%")->first();
                    $publisher = $website->users->first();
                    $websiteID = $website->id;
                    $publisherID = $publisher->id;
                }

                $state = strtolower($response['State']);
                if($state == "reversed")
                    $state = "declined";

                $transaction = TransactionModel::where([
                    'transaction_id'                                    =>       $response['Id'],
                    'internal_advertiser_id'                            =>       $advertiser->id
                ])->first();

                if($transaction && empty($websiteID) && empty($publisherID))
                {
                    $websiteID = $transaction->website_id;
                    $publisherID = $transaction->publisher_id;
                }

                $data = [
                    'internal_advertiser_id'                            =>      $advertiser->id,
                    'external_advertiser_id'                            =>      $advertiser->sid,
                    'transaction_id'                                    =>      $response['Id'],
                    'advertiser_id'                                     =>      $response['CampaignId'],
                    'commission_status'                                 =>      $state,
                    'publisher_id'                                      =>      $publisherID,
                    'website_id'                                        =>      $websiteID,
                    'sub_id'                                            =>      $response['SubId3'] ? $response['SubId3'] : null,
                    'commission_sharing_publisher_id'                   =>      null,
                    'commission_sharing_selected_rate_publisher_id'     =>      null,
                    'payment_id'                                        =>      null,
                    'transaction_query_id'                              =>      null,
                    'campaign_name'                                     =>      $response['ActionTrackerName'] ?? null,
                    'advertiser_name'                                   =>      $response['CampaignName'] ?? null,
                    'site_name'                                         =>      null,
                    'customer_country'                                  =>      $response['CustomerCountry'] ?? null,
                    'click_refs'                                        =>      null,
                    'click_date'                                        =>      $clickDate,
                    'transaction_date'                                  =>      $transactionDate,
                    'validation_date'                                   =>      $validationDate,
                    'commission_type'                                   =>      $response['ReferringType'] ?? null,
                    'voucher_code'                                      =>      $response['PromoCode'] ?? null,
                    'lapse_time'                                        =>      null,
                    'old_sale_amount'                                   =>      null,
                    'old_commission_amount'                             =>      null,
                    'click_device'                                      =>      null,
                    'transaction_device'                                =>      null,
                    'advertiser_country'                                =>      null,
                    'order_ref'                                         =>      $response['Oid'] ?? null,
                    'custom_parameters'                                 =>      null,
                    'transaction_parts'                                 =>      null,
                    'paid_to_publisher'                                 =>      empty($response['ClearedDate']) ? null : true,
                    'tracked_currency_amount'                           =>      null,
                    'tracked_currency_currency'                         =>      null,
                    'ip_hash'                                           =>      null,
                    'url'                                               =>      null,
                    'publisher_url'                                     =>      $response['ReferringDomain'] ?? null,
                    'amended_reason'                                    =>      null,
                    'decline_reason'                                    =>      null,
                    'customer_acquisition'                              =>      null,
                    "source"                                            =>      $advertiser->source
                ];

                if(isset($transaction->paid_to_publisher) && $transaction->paid_to_publisher == TransactionModel::PAYMENT_STATUS_CONFIRM)
                {
                    unset($data['commission_status']);
                }


                $received_sale_amount = abs($response['Amount']);
                $received_commission_amount = abs($response['Payout']);

                if(empty($transaction) || $transaction->is_converted == 0 || ($transaction->received_commission_amount != $received_commission_amount) || ($transaction->commission_amount = 0 || $transaction->commission_amount < 0 || $transaction->commission_amount == null || $transaction->commission_amount == "") && $received_commission_amount < 0)
                {
                    $received_commission_amount_currency = $response['Currency'];
                    if($received_commission_amount_currency == "USD")
                    {
                        $commission_amount = $received_commission_amount ?? "0.00";
                        $commission_amount_currency = $received_commission_amount_currency;
                    }
                    else
                    {
                        $amount = Methods::parseAmountToUSD($received_commission_amount_currency, $received_commission_amount, $transactionDate);
                        $commission_amount = $amount ?? "0.00";
                        $commission_amount_currency = "USD";
                    }

                    $received_sale_amount_currency = $response['Currency'];
                    if($received_sale_amount_currency == "USD")
                    {
                        $sale_amount = $received_sale_amount;
                        $sale_amount_currency = $received_sale_amount_currency;
                    }
                    else
                    {
                        $amount = Methods::parseAmountToUSD($received_sale_amount_currency, $received_sale_amount, $transactionDate);
                        $sale_amount = $amount ?? "0.00";
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
                    'transaction_id'                                    =>       $response['Id'],
                    'internal_advertiser_id'                            =>       $advertiser->id
                ], $data);

            }

//                Methods::customImpactRadius(null, "ADVERTISER ID: {$response['CampaignId']} & TRANSACTION ID: {$response['Id']} FETCHING END");

        }
        else
        {
//                Methods::customImpactRadius($module, "TRANSACTION DATA NOT FOUND");
//                Methods::customImpactRadius(null, $response);
        }


    }

}
