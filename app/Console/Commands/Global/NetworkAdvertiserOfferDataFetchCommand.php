<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use App\Models\NetworkFetchData;
use App\Traits\FetchAdvertiserOfferTrait;
use Illuminate\Console\Command;

class NetworkAdvertiserOfferDataFetchCommand extends Command
{
    use FetchAdvertiserOfferTrait, Base;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network-advertiser-offer-data-fetch';

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

        $checkAdvertiserExtraAlreadyScheduleQuery = NetworkFetchData::where("status", NetworkFetchData::NETWORK_ACTIVE);

        $checkAdvertiserExtraAlreadySchedule = $checkAdvertiserExtraAlreadyScheduleQuery
                                                    ->where("advertiser_transaction_schedule_status", NetworkFetchData::COMPLETED)
                                                    ->where("advertiser_transaction_short_status", NetworkFetchData::COMPLETED)
                                                    ->where("advertiser_payment_status", NetworkFetchData::COMPLETED)
                                                    ->where("advertiser_schedule_status", NetworkFetchData::COMPLETED)
                                                    ->count();

        $fetchDataCheckInProcessNotInProcess = FetchDailyData::whereIn("is_processing", [FetchDailyData::IN_PROCESS, FetchDailyData::IN_PROCESS_NOT])->count();

        $jobCheck = GenerateLink::where('status', Vars::JOB_ACTIVE)->count();

        if ($checkAdvertiserExtraAlreadySchedule == $checkAdvertiserExtraAlreadyScheduleQuery->count() && $fetchDataCheckInProcessNotInProcess == 0 && $jobCheck == 0) {

            $networkFetchData = NetworkFetchData::where("status", NetworkFetchData::NETWORK_ACTIVE)
                                    ->where("advertiser_transaction_schedule_status", NetworkFetchData::COMPLETED)
                                    ->where("advertiser_transaction_short_status", NetworkFetchData::COMPLETED)
                                    ->where("advertiser_payment_status", NetworkFetchData::COMPLETED)
                                    ->where("advertiser_schedule_status", NetworkFetchData::COMPLETED)
                                    ->where("advertiser_coupon_schedule_status", NetworkFetchData::NOT_PROCESSING)
                                    ->where("last_updated_advertiser_offer", '<', now()->format("Y-m-d"))
                                    ->orderBy('sort', 'ASC')->get();

            foreach ($networkFetchData as $data) {

                $data->update([
                    'advertiser_coupon_schedule_status' => NetworkFetchData::PROCESSING
                ]);

                $source = strtoupper($data->name);
                $this->prepareProviderDataFetching(
                    $data,
                    "{$source} ADVERTISER COUPON JOB",
                    "{$source} ADVERTISER COUPON JOB FETCHING START",
                    "{$source} ADVERTISER COUPON JOB FETCHING END"
                );

            }

        }

    }
}
