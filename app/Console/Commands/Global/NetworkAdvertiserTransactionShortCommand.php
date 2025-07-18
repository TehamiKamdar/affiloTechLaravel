<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use App\Models\NetworkFetchData;
use App\Traits\FetchAdvertiserTransactionTrait;
use Illuminate\Console\Command;

class NetworkAdvertiserTransactionShortCommand extends Command
{
    use FetchAdvertiserTransactionTrait, Base;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network-advertiser-transaction-short-data-fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Network Transaction Data Fetch Advertiser in this Command';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $checkAdvertiserOfferAlreadyScheduleQuery = NetworkFetchData::where("status", NetworkFetchData::NETWORK_ACTIVE);

        $checkAdvertiserOfferAlreadySchedule = $checkAdvertiserOfferAlreadyScheduleQuery
                                                            ->where("advertiser_transaction_schedule_status", NetworkFetchData::COMPLETED)
                                                            ->count();

        $fetchDataCheckInProcessNotInProcess = FetchDailyData::whereIn("is_processing", [FetchDailyData::IN_PROCESS, FetchDailyData::IN_PROCESS_NOT])->count();

        $jobCheck = GenerateLink::where('status', Vars::JOB_ACTIVE)->count();

        if($checkAdvertiserOfferAlreadySchedule == $checkAdvertiserOfferAlreadyScheduleQuery->count() && $fetchDataCheckInProcessNotInProcess == 0 && $jobCheck == 0)
        {

            $networkFetchData = NetworkFetchData::where("status", NetworkFetchData::NETWORK_ACTIVE)
                                                ->whereIn("advertiser_transaction_short_status", [NetworkFetchData::NOT_PROCESSING, NetworkFetchData::COMPLETED])
                                                ->where("last_updated_advertiser_transaction_short", '<', now()->subHours(4)->format("Y-m-d H:i:s"))
                                                ->orderBy('sort', 'ASC')->get();

            foreach ($networkFetchData as $data) {

                $data->update([
                    'advertiser_transaction_short_status' => NetworkFetchData::PROCESSING,
                    'last_updated_advertiser_transaction_short' => now()->format(Vars::CUSTOM_DATE_FORMAT_2)
                ]);

                $source = strtoupper($data->name);
                $this->prepareProviderDataFetching(
                    $data,
                    "{$source} ADVERTISER SHORT TRANSACTION JOB",
                    "{$source} ADVERTISER SHORT TRANSACTION JOB FETCHING START",
                    "{$source} ADVERTISER SHORT TRANSACTION JOB FETCHING END",
                );

            }

        }

    }
}
