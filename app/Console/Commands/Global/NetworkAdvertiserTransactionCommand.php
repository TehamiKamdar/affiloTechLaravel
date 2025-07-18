<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use App\Models\NetworkFetchData;
use App\Traits\FetchAdvertiserTransactionTrait;
use Illuminate\Console\Command;

class NetworkAdvertiserTransactionCommand extends Command
{
    use FetchAdvertiserTransactionTrait, Base;

    const MONTHS = 6;
    const SORT = "ASC";

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network-advertiser-transaction-data-fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Network Data Fetch Advertiser Image / Extra Data in this Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $networkFetchData = NetworkFetchData::where("status", NetworkFetchData::NETWORK_ACTIVE)
                                            ->where("advertiser_transaction_schedule_status", NetworkFetchData::NOT_PROCESSING)
                                            ->where("last_updated_advertiser_transaction", '<', now()->format("Y-m-d"))
                                            ->orderBy('sort', 'ASC')->get();

        $fetchDataCheckInProcessNotInProcess = FetchDailyData::whereIn("is_processing", [FetchDailyData::IN_PROCESS, FetchDailyData::IN_PROCESS_NOT])->count();

        $jobCheck = GenerateLink::where('status', Vars::JOB_ACTIVE)->count();

        if(count($networkFetchData) && $fetchDataCheckInProcessNotInProcess == 0 && $jobCheck == 0) {

            foreach ($networkFetchData as $data) {

                $data->update([
                    'advertiser_transaction_schedule_status' => NetworkFetchData::PROCESSING
                ]);

                $source = strtoupper($data->name);
                $this->prepareProviderDataFetching(
                    $data,
                    "{$source} ADVERTISER TRANSACTION JOB",
                    "{$source} ADVERTISER TRANSACTION JOB FETCHING START",
                    "{$source} ADVERTISER TRANSACTION JOB FETCHING END",
                    self::MONTHS
                );

            }

        }

    }

}
