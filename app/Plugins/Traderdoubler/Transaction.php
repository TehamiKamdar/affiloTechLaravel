<?php

namespace App\Plugins\Traderdoubler;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Tradedoubler\TransactionJob;
use App\Models\Advertiser;
use App\Models\Tracking;
use App\Models\Transaction as TransactionModel;
use App\Models\User;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Transaction extends Base
{
    public function callApi($data): void
    {
        $vars = $this->getTradedoublerTransactionStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $limit = $vars['limit'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];
        $exceptionMsg = $vars['exception_msg'];
        $fromDate = $data['start'];
        $toDate = $data['end'];

        $sources = $this->getTradedoublerSourceList();
        $sourceIdz = is_array($sources) ? array_column($sources, "id") : [];

//        Methods::customTradedoubler($module, $startMsg);

        if(empty($sourceIdz))
        {
//            Methods::customTradedoubler($module, $sources);
        }
        else
        {
            foreach ($sourceIdz as $id)
            {
                $fetchLoop = $vars['fetch_loop_true'];
                $offset = $vars['offset'];

                if(empty($id))
                {
//                    Methods::customTradedoubler($module, $exceptionMsg);
                }
                else
                {

                    while ($fetchLoop)
                    {
                        echo "SOURCE: {$id} | OFFSET: {$offset}";
                        echo "\n\n";
                        $transactionData = $this->sendTradedoublerTransactionRequest($id, $fromDate, $toDate, $offset);
                        if(isset($transactionData['items']) && count($transactionData['items']))
                        {
                            $this->storeTransaction($transactionData['items'], $transactionData['reportCurrencyCode']);
                        }
                        else
                        {
                            $fetchLoop = $vars['fetch_loop_false'];
                        }
                        $offset += $limit;
                        $this->changeJobTime();
                    }

                }
            }
        }

//        Methods::customTradedoubler($module, $endMsg);

    }

    public function storeTransaction($transactionData, $currencyCode)
    {
        $vars = $this->getTradedoublerTransactionStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];

        foreach ($transactionData as $response)
        {
            $advertiser = Advertiser::where("advertiser_id", $response['programId'])->where('source', $source)->first();

            if($advertiser)
            {
//                        Methods::customTradedoubler($module, "ADVERTISER ID {$response['advertiserId']} FETCHING START");

                $clickDate = $response['timeOfLastClick'] ?? null;
                if($clickDate)
                    $clickDate = Carbon::parse($clickDate)->format("Y-m-d H:i:s");

                $transactionDate = $response['timeOfTransaction'] ?? null;
                if($transactionDate)
                    $transactionDate = Carbon::parse($transactionDate)->format("Y-m-d H:i:s");

                $publisherID = $websiteID = $subID = null;
                if(isset($response['epi']) && isset($response['epi2']))
                {
                    $u1 = $response['epi'];
                    $checkSubIDExistInTracking = Tracking::select(['id', 'publisher_id', 'website_id', 'sub_id'])->where("sub_id", $u1)->first();
                    if(isset($checkSubIDExistInTracking->id))
                    {
                        $publisherID = $checkSubIDExistInTracking->publisher_id;
                        $websiteID = $checkSubIDExistInTracking->website_id;
                        $subID = $checkSubIDExistInTracking->sub_id;
                    }
                    else {
                        $publisherID = $u1;
                        $websiteID = $response['epi2'];
                    }
                }

                $transaction = TransactionModel::where([
                    'transaction_id'                                    =>       $response['transactionId'],
                    'internal_advertiser_id'                            =>       $advertiser->id
                ])->first();

                if(empty($websiteID) && empty($publisherID) && isset($transaction->website_id) && isset($transaction->publisher_id))
                {
                    $websiteID = $transaction->website_id;
                    $publisherID = $transaction->publisher_id;
                }

                $status = null;
                if($response['status'] == "A")
                    $status = TransactionModel::STATUS_APPROVED;
                elseif($response['status'] == "P")
                    $status = TransactionModel::STATUS_PENDING;
                elseif($response['status'] == "D")
                    $status = TransactionModel::STATUS_DECLINED;

                $data = [
                    'internal_advertiser_id'                            =>      $advertiser->id,
                    'external_advertiser_id'                            =>      $advertiser->sid,
                    'transaction_id'                                    =>      $response['transactionId'],
                    'advertiser_id'                                     =>      $response['programId'],
                    'commission_status'                                 =>      $status,
                    'publisher_id'                                      =>      $publisherID,
                    'website_id'                                        =>      $websiteID,
                    'sub_id'                                            =>      $subID,
                    'advertiser_name'                                   =>      $response['programName'],
                    'customer_country'                                  =>      $response['customerCountry'] ?? null,
                    'click_date'                                        =>      $clickDate,
                    'transaction_date'                                  =>      $transactionDate,
                    'commission_type'                                   =>      $response['type'] ?? null,
                    'voucher_code'                                      =>      $response['voucherCode'] ?? null,
                    'click_device'                                      =>      $response['deviceObject']['deviceType'] ?? null,
                    "source"                                            =>      $advertiser->source,
                ];

                if(isset($transaction->paid_to_publisher) && $transaction->paid_to_publisher == TransactionModel::PAYMENT_STATUS_CONFIRM)
                {
                    unset($data['commission_status']);
                }

                $received_sale_amount = abs($response['orderValue']);
                $received_commission_amount = abs($response['commission']);

                if(empty($transaction) || $transaction->is_converted == 0 || ($transaction->received_commission_amount != $received_commission_amount) || ($transaction->commission_amount = 0 || $transaction->commission_amount < 0 || $transaction->commission_amount == null || $transaction->commission_amount == "") && $received_commission_amount < 0)
                {
                    $received_commission_amount_currency = $currencyCode;
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

                    $received_sale_amount_currency = $currencyCode;
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
                    'transaction_id'                                    =>       $response['transactionId'],
                    'internal_advertiser_id'                            =>       $advertiser->id
                ], $data);

//                        Methods::customTradedoubler(null, "ADVERTISER ID {$response['programId']} FETCHING END");

            }
        }
    }

}
