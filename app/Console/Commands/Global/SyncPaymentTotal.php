<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Vars;
use App\Models\PaymentHistory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncPaymentTotal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync-payment-total';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Payment Total every 4 hours.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $payments = PaymentHistory::select(['id', 'transaction_idz', 'amount', 'commission_amount', 'lc_commission_amount'])->where("status", "pending")->where("is_matched", 0)->get();

        foreach ($payments as $payment)
        {
            $transaction = DB::table("transactions")
                ->select(DB::raw('SUM(sale_amount) as total_sale_amount,
                      SUM(commission_amount) as total_commission_amount,
                        count(*) as total_transactions'))
                ->whereIn("transaction_id", $payment->transaction_idz)->first();

            $commissionAmount = $transaction->total_commission_amount;
            $saleAmount = $transaction->total_sale_amount;
            $staticCommission = Vars::COMMISSION_PERCENTAGE;
            $payment->update([
                "amount" => $saleAmount,
                "commission_amount" => $commissionAmount,
                "lc_commission_amount" => "0.{$staticCommission}" * $commissionAmount,
                "is_matched" => 1
            ]);
        }
    }
}
