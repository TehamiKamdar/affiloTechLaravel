<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use App\Models\NetworkFetchData;
use App\Traits\FetchAdvertiserPaymentTrait;
use App\Traits\FetchAdvertiserTransactionTrait;
use Illuminate\Console\Command;

class NetworkAdvertiserPaymentCommand extends Command
{
    use FetchAdvertiserPaymentTrait, Base;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'network-advertiser-payment-data-fetch';

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
                                                            ->where("advertiser_transaction_short_status", NetworkFetchData::COMPLETED)
                                                            ->count();

        $fetchDataCheckInProcessNotInProcess = FetchDailyData::whereIn("is_processing", [FetchDailyData::IN_PROCESS, FetchDailyData::IN_PROCESS_NOT])->count();

        $jobCheck = GenerateLink::where('status', Vars::JOB_ACTIVE)->count();

        if($checkAdvertiserOfferAlreadySchedule == $checkAdvertiserOfferAlreadyScheduleQuery->count() && $fetchDataCheckInProcessNotInProcess == 0 && $jobCheck == 0)
        {

            $networkFetchData = NetworkFetchData::where("status", NetworkFetchData::NETWORK_ACTIVE)
                                                ->where("advertiser_transaction_schedule_status", NetworkFetchData::COMPLETED)
                                                ->where("advertiser_transaction_short_status", NetworkFetchData::COMPLETED)
                                                ->where("last_updated_advertiser_payment", '<', now()->format(Vars::CUSTOM_DATE_FORMAT_3))
                                                ->orderBy('sort', 'ASC')->get();

            foreach ($networkFetchData as $data) {

                $data->update([
                    'advertiser_payment_status' => NetworkFetchData::PROCESSING,
                ]);

                $source = strtoupper($data->name);
                $this->prepareProviderDataFetching(
                    $data,
                    "{$source} ADVERTISER PAYMENT JOB",
                    "{$source} ADVERTISER PAYMENT JOB FETCHING START",
                    "{$source} ADVERTISER PAYMENT JOB FETCHING END",
                );

            }

        }

    }
}
