<?php

namespace App\Console\Commands;

use App\Helper\Static\Vars;
use App\Helper\Static\Methods;
use App\Models\FetchDailyData;
use App\Models\Setting;
use App\Traits\Main;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\SyncData as SyncDataModel;

class SyncData extends Command
{
    use Main;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Data for (Advertisers, Offers, Transactions & Payments.).';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        
        $sync = SyncDataModel::select(['id', 'type'])->where("is_sync", Vars::NOT_SYNC)->first();
        
        if(isset($sync->id))
        {
            switch ($sync->type)
            {
                case Vars::PAYMENT:
                    $this->syncPayments();
                    break;

                case Vars::TRANSACTION:
                    $this->syncTransactions();
                    break;

                case Vars::TRANSACTION_SHORT:
                    $this->syncTransactions(true);
                    break;

                case Vars::ADVERTISER:
                    $this->syncAdvertisers();
                    break;

                case Vars::COUPON:
                    $this->syncCoupons();
                    break;

                default:
                    info("COMMAND NOT FOUND..");
                    break;
            }
        }
    }

    private function syncAdvertisers()
    {
        $sources = SyncDataModel::where("type", Vars::ADVERTISER)->where("is_sync", Vars::NOT_SYNC)->get();
        foreach ($sources as $source)
        {
            $source = $source->source;
            $url = env("APP_SERVER_API_URL");
            $response = Http::get("{$url}api/sync-advertisers?source={$source}");
           
            if($response->ok())
            {
                $advertisers = $response->json();
               
                for ($no = 1; $no <= ceil($advertisers['total'] / $advertisers['per_page']); $no++)
                {
                    FetchDailyData::updateOrCreate([
                        "path" => "SyncAdvertiserJob",
                        "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                        "offset" => $no,
                        "queue" => 'admin_work',
                        "source" => $source,
                        "type" => Vars::ADVERTISER
                    ], [
                        "name" => "Sync Advertiser Job",
                        "payload" => json_encode([
                            "source" => $source,
                            "page" => $no
                        ]),
                        "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                        "sort" => $this->setSortingFetchDailyData($source)
                    ]);
                }

                SyncDataModel::where("type", Vars::ADVERTISER)->update([
                    "is_sync" => Vars::SYNC
                ]);
            }
        }
    }

    private function syncPayments()
    {
        $sources = SyncDataModel::where("type", Vars::PAYMENT)->where("is_sync", Vars::NOT_SYNC)->get();
        foreach ($sources as $source)
        {
            $source = $source->source;
            $url = env("APP_SERVER_API_URL");
            $response = Http::get("{$url}api/sync-payments?source={$source}");
            if($response->ok())
            {
                $advertisers = $response->json();
                for ($no = 1; $no <= ceil($advertisers['total'] / $advertisers['per_page']); $no++)
                {
                    FetchDailyData::updateOrCreate([
                        "path" => "SyncPaymentJob",
                        "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                        "offset" => $no,
                        "queue" => 'admin_work',
                        "source" => $source,
                        "type" => Vars::PAYMENT
                    ], [
                        "name" => "Sync Payment Job",
                        "payload" => json_encode([
                            "source" => $source,
                            "page" => $no
                        ]),
                        "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                        "sort" => $this->setSortingFetchDailyData($source)
                    ]);
                }

                SyncDataModel::where("type", Vars::PAYMENT)->update([
                    "is_sync" => Vars::SYNC
                ]);
            }
        }
    }
    private function syncTransactions($short = false)
    {
        $type = $short ? Vars::TRANSACTION_SHORT : Vars::TRANSACTION;
        $sources = SyncDataModel::where("type", $type)->where("is_sync", Vars::NOT_SYNC)->get();
        Methods::trackingClicks("syncdailsources152y",$sources);
        foreach ($sources as $source)
        {
            if($short) {
                $this->onMonthSyncTransactions($source->source, $type);
            }
            else
            {
                $this->onCustomMonthsSyncTransactions($source->source, $type);
            }
        }
    }

    private function syncCoupons()
    {
        $sources = SyncDataModel::where("type", Vars::COUPON)->where("is_sync", Vars::NOT_SYNC)->get();
        foreach ($sources as $source)
        {
            $url = env("APP_SERVER_API_URL");
            $response = Http::get("{$url}api/sync-offers?source={$source->source}");
            if($response->ok())
            {
                $advertisers = $response->json();
                for ($no = 1; $no <= ceil($advertisers['total'] / $advertisers['per_page']); $no++)
                {

                    FetchDailyData::updateOrCreate([
                        "path" => "SyncCouponJob",
                        "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                        "offset" => $no,
                        "queue" => 'admin_work',
                        "source" => $source,
                        "type" => Vars::COUPON
                    ], [
                        "name" => "Sync Coupon Job",
                        "payload" => json_encode([
                            "source" => $source,
                            "page" => $no
                        ]),
                        "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                        "sort" => $this->setSortingFetchDailyData($source)
                    ]);
                }

                SyncDataModel::where("type", Vars::COUPON)->update([
                    "is_sync" => Vars::SYNC
                ]);
            }
        }
    }

    private function onMonthSyncTransactions($source, $type)
    {
        $url = env("APP_SERVER_API_URL");
        $url = "{$url}api/sync-transactions?source={$source}";
        $start_date = now()->subDays(90)->format("Y-m-d");
        $end_date = now()->addDays(1)->format("Y-m-d");
        $url = "{$url}&start_date=$start_date&end_date=$end_date";
        $response = Http::get($url);
        \Log::channel('response')->info($response);
        Methods::trackingClicks("syncdailyresponse",$url);
        Methods::trackingClicks("syncdailyresponse",$response);
        Methods::trackingClicks("syncdailyresponse",$response->json());
        $this->makeTransactionJobs($response, $source, $type, $start_date, $end_date);
    }

    private function onCustomMonthsSyncTransactions($source, $type)
    {
        // Get the date 6 months ago from the current date
        $startDate = Carbon::now()->subMonths(9)->startOfMonth();

        // Loop through each month within the past 6 months
        for ($i = 0; $i <= 9; $i++) {
            // Calculate the starting and ending dates of the current month
            $month_start_date = $startDate->copy()->startOfMonth();
            $month_end_date = $startDate->copy()->endOfMonth();

            // Output the starting and ending dates for the current month
            $url = env("APP_SERVER_API_URL");
            $url = "{$url}api/sync-transactions?source={$source}";
            $start_date = $month_start_date->toDateString();
            $end_date = $month_end_date->toDateString();
            $url = "{$url}&start_date=$start_date&end_date=$end_date";
            $response = Http::get($url);
            $this->makeTransactionJobs($response, $source, $type, $start_date, $end_date);

            // Move to the next month
            $startDate->addMonth();
        }
    }

    private function makeTransactionJobs($response, $source, $type, $start_date, $end_date)
    {

        $name = $type == Vars::TRANSACTION ? "Sync {$source} Transaction Job" : "Sync {$source} Transaction Short Job";

        if($response->ok())
        {
            $get = $this->getQueueName($source);
            $queue = strtolower($get);

            $advertisers = $response->json();

            if(isset($advertisers['total']) && isset($advertisers['per_page']))
            {
                for ($no = 1; $no <= ceil($advertisers['total'] / $advertisers['per_page']); $no++)
                {
                    FetchDailyData::updateOrCreate([
                        "path" => "Sync{$get}TransactionJob",
                        "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                        "offset" => $no,
                        "queue" => "{$queue}_trans",
                        "source" => $source,
                        "type" => $type,
                        "start_date" => $start_date,
                        "end_date" => $end_date,
                    ], [
                        "name" => $name,
                        "payload" => json_encode([
                            "source" => $source,
                            "page" => $no,
                            "start_date" => $start_date,
                            "end_date" => $end_date,
                        ]),
                        "status" => 1,
                        "is_processing" => 0,
                        "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                        "sort" => $this->setSortingFetchDailyData($source)
                    ]);
                }
            }

            SyncDataModel::where("source", $source)->update([
                "is_sync" => Vars::SYNC
            ]);
        }
    }

    private function getQueueName($source)
    {
        return str_replace(" ", "", $source);
    }
}
