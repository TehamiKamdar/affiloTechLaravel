<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use App\Models\NetworkFetchData;
use App\Traits\FetchExtraAdvertiserInfoTrait;
use Illuminate\Console\Command;

class NetworkExtraAdvertiserDataFetchCommand extends Command
{
    use FetchExtraAdvertiserInfoTrait, Base;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network-extra-advertiser-data-fetch';

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
        $checkAdvertisersAlreadyScheduleQuery = NetworkFetchData::where("status", NetworkFetchData::NETWORK_ACTIVE);

        $checkAdvertisersAlreadySchedule = $checkAdvertisersAlreadyScheduleQuery
                                    ->where("advertiser_schedule_status", NetworkFetchData::COMPLETED)
                                    ->where("advertiser_coupon_schedule_status", NetworkFetchData::COMPLETED)
                                    ->where("advertiser_transaction_schedule_status", NetworkFetchData::COMPLETED)
                                    ->where("advertiser_transaction_short_status", NetworkFetchData::COMPLETED)
                                    ->where("advertiser_payment_status", NetworkFetchData::COMPLETED)
                                    ->where("last_updated_advertiser_transaction", '<', now()->format("Y-m-d"))
                                    ->count();

        $fetchDataCheckInProcessNotInProcess = FetchDailyData::whereIn("is_processing", [FetchDailyData::IN_PROCESS, FetchDailyData::IN_PROCESS_NOT])->count();

        $jobCheck = GenerateLink::where('status', Vars::JOB_ACTIVE)->count();

        if($checkAdvertisersAlreadySchedule == $checkAdvertisersAlreadyScheduleQuery->count() && $fetchDataCheckInProcessNotInProcess == 0 && $jobCheck == 0)
        {
            $networkFetchData = NetworkFetchData::where("status", NetworkFetchData::NETWORK_ACTIVE)
                                                ->where("advertiser_schedule_status", NetworkFetchData::COMPLETED)
                                                ->where("advertiser_coupon_schedule_status", NetworkFetchData::COMPLETED)
                                                ->where("advertiser_transaction_schedule_status", NetworkFetchData::COMPLETED)
                                                ->where("advertiser_transaction_short_status", NetworkFetchData::COMPLETED)
                                                ->where("advertiser_payment_status", NetworkFetchData::COMPLETED)
                                                ->where("last_updated_advertiser_extra", '<', now()->format("Y-m-d"))
                                                ->orderBy('sort', 'ASC')->get();

            foreach ($networkFetchData as $data) {

                $data->update([
                    'advertiser_extra_schedule_status' => NetworkFetchData::PROCESSING
                ]);

                $source = strtoupper($data->name);
                $this->prepareProviderDataFetching(
                    $data,
                    "{$source} ADVERTISER EXTRA JOB",
                    "{$source} ADVERTISER EXTRA JOB FETCHING START",
                    "{$source} ADVERTISER EXTRA JOB FETCHING END"
                );

            }
        }

    }
}
