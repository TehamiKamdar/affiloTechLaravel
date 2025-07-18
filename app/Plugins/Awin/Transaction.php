<?php

namespace App\Plugins\Awin;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\Transaction as TransactionModel;
use App\Models\User;
use App\Models\Website;
use App\Traits\JobTrait;
use App\Traits\RequestTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Transaction extends Base
{

    public function callApi($parms)
    {
        $transactionsData = $this->sendAwinTransactionRequest($parms['start'], $parms['end']);
        $transactionsData = is_array($transactionsData) ? $transactionsData : @json_decode($transactionsData, true);
        $this->makeQueueableTransaction($transactionsData);
        $this->changeJobTime();
    }

    private function makeQueueableTransaction($transactionsData)
    {
        $vars = $this->getAwinTransactionStaticVar();
        if(isset($transactionsData['error']))
        {
            Methods::customError($vars['module_name'], $transactionsData['error']);
        } elseif (count($transactionsData))
        {
            foreach ($transactionsData as $response)
            {
                if(isset($response['clickRefs']['clickRef']) || isset($response['clickRefs']['clickRef2']) || isset($response['clickRefs']['clickRef3']) || isset($response['publisherUrl']))
                {
                    $this->storeTransaction($response);
                }
            }
        }
    }

    private function storeTransaction($response)
    {
        $vars = $this->getAwinTransactionStaticVar();

        $advertiser = Advertiser::where("advertiser_id", $response['advertiserId'])->where('source', Vars::AWIN)->first();

        if($advertiser)
        {
//                Methods::customAwin("TRANSACTION JOB", "ADVERTISER ID {$response['advertiserId']} & TRANSACTION ID: {$response['id']} FETCHING START");

            $clickDate = $response['clickDate'] ?? null;
            if($clickDate)
                $clickDate = Carbon::parse($clickDate)->format("Y-m-d H:i:s");

            $transactionDate = $response['transactionDate'] ?? null;
            if($transactionDate)
                $transactionDate = Carbon::parse($transactionDate)->format("Y-m-d H:i:s");

            $validationDate = $response['validationDate'] ?? null;
            if($validationDate)
                $validationDate = Carbon::parse($validationDate)->format("Y-m-d H:i:s");

            $publisherID = $websiteID = null;
            if(isset($response['clickRefs']['clickRef']) && isset($response['clickRefs']['clickRef2']))
            {
                $publisherID = $response['clickRefs']['clickRef'];
                $websiteID = $response['clickRefs']['clickRef2'];
            }
            elseif(isset($response['clickRefs']['clickRef']))
            {
                $publisherID = $response['clickRefs']['clickRef'];
                $user = User::where('id', $publisherID)->first();
                $websiteID = $user->websites[0]->id ?? null;
            }
            elseif (isset($response['publisherUrl']))
            {
                $url = $response['publisherUrl'];
                $url = parse_url($url, PHP_URL_HOST);
                $website = Website::with('users:id')->where('url', 'LIKE', "%$url%")->first();
                $publisher = $website->users->first();
                $websiteID = $website->id;
                $publisherID = $publisher->id;
            }
            else {
                Methods::customError($vars['module_name'], $response);
            }

            $transaction = TransactionModel::where([
                'transaction_id'                                    =>       $response['id'],
                'internal_advertiser_id'                            =>       $advertiser->id
            ])->first();

            if(empty($websiteID) && empty($publisherID))
            {
                $websiteID = $transaction->website_id;
                $publisherID = $transaction->publisher_id;
            }

            $data = [
                'internal_advertiser_id'                            =>      $advertiser->id,
                'external_advertiser_id'                            =>      $advertiser->sid,
                'advertiser_id'                                     =>      $response['advertiserId'],
                'commission_status'                                 =>      $response['commissionStatus'],
                'publisher_id'                                      =>      $publisherID,
                'website_id'                                        =>      $websiteID,
                'commission_sharing_publisher_id'                   =>      $response['commissionSharingPublisherId'] ?? null,
                'commission_sharing_selected_rate_publisher_id'     =>      $response['commissionSharingSelectedRatePublisherId'] ?? null,
                'payment_id'                                        =>      $response['paymentId'] ?? null,
                'sub_id'                                            =>      $response['clickRefs']['clickRef3'] ?? null,
                'transaction_query_id'                              =>      $response['transactionQueryId'] ?? null,
                'campaign_name'                                     =>      $response['campaign'] ?? null,
                'advertiser_name'                                   =>      $advertiser->name ?? null,
                'site_name'                                         =>      $response['siteName'] ?? null,
                'customer_country'                                  =>      $response['customerCountry'] ?? null,
                'click_refs'                                        =>      $response['clickRefs'] ?? null,
                'click_date'                                        =>      $clickDate,
                'transaction_date'                                  =>      $transactionDate,
                'validation_date'                                   =>      $validationDate,
                'commission_type'                                   =>      $response['type'] ?? null,
                'voucher_code'                                      =>      $response['voucherCode'] ?? null,
                'lapse_time'                                        =>      $response['lapseTime'] ?? null,
                'old_sale_amount'                                   =>      $response['oldSaleAmount']['amount'] ?? null,
                'old_commission_amount'                             =>      $response['oldCommissionAmount']['amount'] ?? null,
                'click_device'                                      =>      $response['clickDevice'] ?? null,
                'transaction_device'                                =>      $response['transactionDevice'] ?? null,
                'advertiser_country'                                =>      $response['advertiserCountry'] ?? null,
                'order_ref'                                         =>      $response['orderRef'] ?? null,
                'custom_parameters'                                 =>      $response['customParameters'] ?? null,
                'transaction_parts'                                 =>      $response['transactionParts'] ?? null,
                'paid_to_publisher'                                 =>      $response['paidToPublisher'] ?? null,
                'tracked_currency_amount'                           =>      $response['trackedCurrencyAmount']['amount'] ?? null,
                'tracked_currency_currency'                         =>      $response['trackedCurrencyAmount']['currency'] ?? null,
                'ip_hash'                                           =>      $response['ipHash'] ?? null,
                'url'                                               =>      $response['url'] ?? null,
                'publisher_url'                                     =>      $response['publisherUrl'] ?? null,
                'amended_reason'                                    =>      $response['amendReason'] ?? null,
                'decline_reason'                                    =>      $response['declineReason'] ?? null,
                'customer_acquisition'                              =>      $response['customerAcquisition'] ?? null,
                "source"                                            =>      $advertiser->source,
            ];

            if(isset($transaction->paid_to_publisher) && $transaction->paid_to_publisher == TransactionModel::PAYMENT_STATUS_CONFIRM)
            {
                unset($data['commission_status']);
            }

            $received_sale_amount = abs($response['saleAmount']['amount']);
            $received_commission_amount = abs($response['commissionAmount']['amount']);

            if(empty($transaction) || $transaction->is_converted == 0 || ($transaction->received_commission_amount != $received_commission_amount) || ($transaction->commission_amount = 0 || $transaction->commission_amount < 0 || $transaction->commission_amount == null || $transaction->commission_amount == "") && $received_commission_amount < 0) {
                $received_commission_amount_currency = $response['commissionAmount']['currency'];
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

                $received_sale_amount_currency = $response['saleAmount']['currency'];
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
                'transaction_id'                                    =>       $response['id'],
                'internal_advertiser_id'                            =>       $advertiser->id
            ], $data);

//                Methods::customAwin(null, "ADVERTISER ID {$response['advertiserId']} & TRANSACTION ID: {$response['id']} FETCHING END");

        }

    }

}
