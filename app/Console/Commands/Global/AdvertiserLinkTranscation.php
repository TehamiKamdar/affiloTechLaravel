<?php

namespace App\Console\Commands\Global;

use App\Models\Advertiser;
use App\Models\Transaction;
use Illuminate\Console\Command;

class AdvertiserLinkTranscation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'advertiser-link-transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if the transaction not linked the advertiser that link link to the advertiser..';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $transactions = Transaction::where("external_advertiser_id", 0)->get();
        foreach ($transactions as $transaction)
        {
            $advertiser = Advertiser::where('advertiser_id', $transaction->advertiser_id)->first();
            $transaction->update([
                "external_advertiser_id" => $advertiser->sid ?? 0
            ]);
        }
    }
}
