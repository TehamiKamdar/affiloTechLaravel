<?php

namespace App\Console\Commands\Global;

use App\Models\NetworkFetchData;
use Illuminate\Console\Command;

class NetworkStatusUpdateOnDailyBasisCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network-status-update-on-daily-basis';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Network Status Update On Daily Basis Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $idz = NetworkFetchData::select('id')
                        ->where("status", NetworkFetchData::NETWORK_ACTIVE)
                        ->whereIn("advertiser_schedule_status", [NetworkFetchData::NOT_PROCESSING, NetworkFetchData::PROCESSING])
                        ->whereIn("advertiser_extra_schedule_status", [NetworkFetchData::NOT_PROCESSING, NetworkFetchData::PROCESSING])
                        ->whereIn("advertiser_coupon_schedule_status", [NetworkFetchData::NOT_PROCESSING, NetworkFetchData::PROCESSING])
                        ->get()->pluck('id')->toArray();

        if(count($idz) == 0)
        {
            NetworkFetchData::where("status", NetworkFetchData::NETWORK_ACTIVE)
                ->update([
                    "advertiser_schedule_status" => NetworkFetchData::NOT_PROCESSING,
                    "advertiser_extra_schedule_status" => NetworkFetchData::NOT_PROCESSING,
                    "advertiser_coupon_schedule_status" => NetworkFetchData::NOT_PROCESSING,
                    "advertiser_transaction_schedule_status" => NetworkFetchData::NOT_PROCESSING,
                    "advertiser_transaction_short_status" => NetworkFetchData::NOT_PROCESSING,
                    "advertiser_payment_status" => NetworkFetchData::NOT_PROCESSING,
                ]);
        }
    }
}
