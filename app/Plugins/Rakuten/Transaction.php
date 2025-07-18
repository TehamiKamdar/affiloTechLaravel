<?php

namespace App\Plugins\Rakuten;

use App\Helper\Static\Methods;
use App\Models\Advertiser;
use App\Models\Tracking;
use App\Models\Transaction as TransactionModel;
use App\Models\Website;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Transaction extends Base
{
    public function callApi($data): void
    {

        $page = 1;
        $loop2 = true;
        while ($loop2)
        {

            echo $page;
            echo "\n\n";
            $transactions = $this->sendRakutenTransactionRequest($data['start'], $data['end'], $page);
            if(is_array($transactions) && count($transactions))
            {
                foreach ($transactions as $response)
                {
                    if(isset($response['etransaction_id']))
                    {
                        $this->storeTransaction($response);
                    }
                }

//                $this->changeJobTime(5);
                $page++;
            }
            else
            {
                $vars = $this->getRakutenTransactionStaticVar();
                $source = $vars['source'];
                $module = $vars['module_name'];
                Methods::customRakuten($module, "======= TRANSACTION VARIABLE =======");
                Methods::customRakuten($module, $transactions);
                $page = 1;
                $loop2 = false;
            }

        }

    }

    private function storeTransaction($response)
    {
        $vars = $this->getRakutenTransactionStaticVar();
        $source = $vars['source'];
        $module = $vars['module_name'];

        $id = $response['etransaction_id'] ?? null;

        $advertiser = Advertiser::where("advertiser_id", $response['advertiser_id'])->where('source', $source)->first();

        $transactionDate = $response['transaction_date'] ?? null;
        if($transactionDate)
            $transactionDate = Carbon::parse($transactionDate)->format("Y-m-d H:i:s");

        if($advertiser)
        {
            $publisherID = $websiteID = $subID = null;
            if(isset($response['u1']) && $response['u1'])
            {
                $u1 = $response['u1'];
                $checkSubIDExistInTracking = Tracking::select(['id', 'publisher_id', 'website_id', 'sub_id'])->where("sub_id", $u1)->first();
                if(isset($checkSubIDExistInTracking->id))
                {
                    $publisherID = $checkSubIDExistInTracking->publisher_id;
                    $websiteID = $checkSubIDExistInTracking->website_id;
                    $subID = $checkSubIDExistInTracking->sub_id;
                }
                else {
                    $website = Website::where('id', $u1)->first();
                    $publisherID = $website->users[0]['id'] ?? null;
                    $websiteID = $u1;
                }
            }

            $transaction = TransactionModel::where([
                'transaction_id'                                    =>       $id,
                'internal_advertiser_id'                            =>       $advertiser->id
            ])->first();

            if(empty($websiteID) && empty($publisherID))
            {
                $websiteID = $transaction->website_id;
                $publisherID = $transaction->publisher_id;
            }

            $state = TransactionModel::STATUS_PENDING;
            if($response['is_event'] == "N")
            {
                $state = TransactionModel::STATUS_APPROVED;
            }

            $data = [
                'external_advertiser_id'                            =>      $advertiser->sid,
                'advertiser_id'                                     =>      $response['advertiser_id'],
                'commission_status'                                 =>      $state,
                'publisher_id'                                      =>      $publisherID,
                'website_id'                                        =>      $websiteID,
                'sub_id'                                            =>      $subID,
                'advertiser_name'                                   =>      $advertiser->name,
                'campaign_name'                                     =>      $response['product_name'] ?? null,
                'transaction_date'                                  =>      $transactionDate,
                "source"                                            =>      $advertiser->source,
                'order_ref'                                         =>      $response['order_id'] ?? null
            ];

            if(isset($transaction->paid_to_publisher) && $transaction->paid_to_publisher == TransactionModel::PAYMENT_STATUS_CONFIRM)
            {
                unset($data['commission_status']);
            }

            $received_sale_amount = abs($response['sale_amount']);
            $received_commission_amount = abs($response['commissions']);

            if(empty($transaction) || $transaction->is_converted == 0 || ($transaction->received_commission_amount != $received_commission_amount) || ($transaction->commission_amount = 0 || $transaction->commission_amount < 0 || $transaction->commission_amount == null || $transaction->commission_amount == "") && $received_commission_amount < 0)
            {
                $received_commission_amount_currency = $response['currency'];
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

                $received_sale_amount_currency = $response['currency'];
                if($received_sale_amount_currency == "USD")
                {
                    $sale_amount = $received_sale_amount;
                    $sale_amount_currency = $received_sale_amount_currency;
                }
                else
                {
                    $amount = Methods::parseAmountToUSD($received_sale_amount_currency, abs($received_sale_amount), $transactionDate);
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
                'transaction_id'                                    =>       $id,
                'internal_advertiser_id'                            =>       $advertiser->id
            ], $data);

        }

    }

}
