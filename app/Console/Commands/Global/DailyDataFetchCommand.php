<?php

namespace App\Console\Commands\Global;

use App\Console\Commands\SyncData;
use App\Helper\Static\Vars;
use App\Jobs\MakeHistory;
use App\Jobs\ManualApprovalNetworkActiveAdvertiser;
use App\Jobs\ManualApprovalNetworkCancelAdvertiser;
use App\Jobs\ManualApprovalNetworkHoldAdvertiser;
use App\Jobs\Move\MoveDataJob;
use App\Jobs\Sync\AdvertiserJob as SyncAdvertiserJob;
use App\Jobs\Sync\AdvertiserCustomJob as SyncAdvertiserCustomJob;
use App\Jobs\Sync\CouponJob as SyncCouponJob;
use App\Jobs\Sync\LinkJob as SyncLinkJob;
use App\Jobs\Sync\StatusChangeJob as SyncStatusChangeJob;
use App\Jobs\Sync\UserJob as SyncUserJob;
use App\Jobs\Sync\TransactionJob as SyncTransactionJob;
use App\Jobs\Sync\PaymentJob as SyncPaymentJob;
use App\Jobs\Sync\AdvertiserStatusJob as SyncAdvertiserStatusJob;
use App\Jobs\Data\AdvertiserLockUnloadJob as DataAdvertiserLockUnloadJob;
use App\Jobs\Data\ApplyAdvertiserJob as DataAdvertiserPublisherStatucChange;
use App\Jobs\Data\GenerateLinkJob as DataGenerateLinkJob;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DailyDataFetchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-data-fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Data Fetch the API Server through command.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try
        {

//        $result = Methods::getQueueJobsWithCondition();
            $jobCheck = FetchDailyData::select('id')->where('is_processing', 0)->count();

            $settings = Setting::select('value')->where("key", 'daily_fetch')->first();

            $dataSyncCheck = \App\Models\SyncData::select('id')->where('is_sync', 0)->count();

            if($jobCheck > 0)// && count($result) == 0)
            {
                $fetchDailyData = FetchDailyData::select('source')->where('is_processing', Vars::JOB_NOT_PROCESS)->where('status', Vars::JOB_ACTIVE)->whereNotIn('type', [Vars::TRANSACTION, Vars::TRANSACTION_SHORT])->groupBy('source')->get();

                foreach ($fetchDailyData as $key => $source)
                {

                    $jobs = DB::table('fetch_daily_data')
                                ->select(['id', 'payload', 'is_processing', 'queue', 'path'])
                                ->where('status', Vars::JOB_ACTIVE)
                                ->where('source', $source->source)
                                ->whereIn('is_processing', [Vars::JOB_NOT_PROCESS, Vars::JOB_ERROR])
                                ->whereNotIn('type', [Vars::TRANSACTION, Vars::TRANSACTION_SHORT])
                                ->orderBy('date', 'ASC')
                                ->orderBy('sort', 'ASC')
                                ->take(20)
                                ->get();

                    if(count($jobs))
                    {

                        // Get the IDs of the jobs to update
                        $jobIds = $jobs->pluck('id')->toArray();

                        FetchDailyData::whereIn('id', $jobIds)->update([
                            'is_processing' => Vars::JOB_IN_PROCESS
                        ]);

                        foreach ($jobs as $job)
                        {

                            $payload = json_decode($job->payload, true);
                            $payload['job_id'] = $job->id;

                            if(count($fetchDailyData) == ($key + 1))
                            {
                                $payload['is_status_change'] = true;
                            }
                            else
                            {
                                $payload['is_status_change'] = false;
                            }

                            $queue = $job->queue;
                            switch ($job->path)
                            {

                                case "SyncAdvertiserJob":
                                    SyncAdvertiserJob::dispatch($payload)->onQueue($queue);
                                    break;

                                case "SyncCouponJob":
                                    SyncCouponJob::dispatch($payload)->onQueue($queue);
                                    break;

                                case "ManualApprovalNetworkCancelAdvertiser":
                                    ManualApprovalNetworkCancelAdvertiser::dispatch($payload)->onQueue($queue);
                                    break;

                                case "ManualApprovalNetworkHoldAdvertiser":
                                    ManualApprovalNetworkHoldAdvertiser::dispatch($payload)->onQueue($queue);
                                    break;

                                case "ManualApprovalNetworkActiveAdvertiser":
                                    ManualApprovalNetworkActiveAdvertiser::dispatch($payload)->onQueue($queue);
                                    break;
                                    
                                    case "DataAdvertiserLockUnloadJob":
                                    DataAdvertiserLockUnloadJob::dispatch($payload)->onQueue($queue);
                                    break;
                                    
                                    case "DataAdvertiserPublisherStatucChange":
                                        DataAdvertiserPublisherStatucChange::dispatch($payload)->onQueue($queue);
                                        break;
                                        
                                        case "DataGenerateLinkJob":
                                            DataGenerateLinkJob::dispatch($payload)->onQueue($queue);
                                            break;

//                                    case "SyncLinkJob":
//                                        SyncLinkJob::dispatch($payload)->onQueue($queue);
//                                        break;

                                case "SyncStatusChangeJob":
                                    SyncStatusChangeJob::dispatch($payload)->onQueue($queue);
                                    break;

                                case "SyncUserJob":
                                    SyncUserJob::dispatch($payload)->onQueue($queue);
                                    break;

                                case "SyncAdvertiserStatusJob":
                                    SyncAdvertiserStatusJob::dispatch($payload)->onQueue($queue);
                                    break;

                                case "SyncAdvertiserCustomJob":
                                    SyncAdvertiserCustomJob::dispatch($payload)->onQueue($queue);
                                    break;

                                case "SyncPaymentJob":
                                    SyncPaymentJob::dispatch($payload)->onQueue($queue);
                                    break;

                                case "AdvertiserMoveTrackingJob":
                                case "AdvertiserMoveTrackingDetailJob":
                                case "AdvertiserMoveDeeplinkTrackingJob":
                                case "AdvertiserMoveDeeplinkTrackingDetailJob":
                                case "AdvertiserMoveCouponTrackingJob":
                                case "AdvertiserMoveCouponTrackingDetailJob":
                                case "AdvertiserApplyMoveTrackingJob":
                                case "AdvertiserApplyMoveTrackingDetailJob":
                                case "AdvertiserApplyMoveDeeplinkTrackingJob":
                                case "AdvertiserApplyMoveDeeplinkTrackingDetailJob":
                                case "AdvertiserApplyMoveCouponTrackingJob":
                                case "AdvertiserApplyMoveCouponTrackingDetailJob":
                                    MoveDataJob::dispatch($payload)->onQueue($queue);
                                    break;

                                case "MakeHistoryTrackingJob":
                                case "MakeHistoryDeeplinkTrackingJob":
                                    MakeHistory::dispatch($payload)->onQueue($queue);
                                    break;

                                default:
                                    break;

                            }
                            
                           

                        }
                    }
                }

            }

            if($settings->value == 1)
            {
                $jobs = FetchDailyData::whereIn("is_processing", [Vars::JOB_NOT_PROCESS, Vars::JOB_IN_PROCESS, Vars::JOB_ERROR])->count();

                if($jobs == 0)
                {
                    FetchDailyData::updateOrCreate([
                        "path" => "SyncStatusChangeJob",
                        "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                        "key" => 0,
                        "queue" => Vars::ADMIN_WORK,
                        "source" => Vars::GLOBAL,
                        "type" => Vars::GLOBAL
                    ], [
                        "name" => "Sync Status Change Job for API Server",
                        "payload" => json_encode([
                            "status" => 0,
                        ]),
                        "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    ]);

//                    StatusChangeJob::dispatch(['status' => 0])->onQueue(Vars::ADMIN_WORK);

                    Setting::updateOrCreate([
                        'key' => 'daily_fetch'
                    ], [
                        'value' => 0,
                    ]);
                }
            }

        }
        catch (\Exception  $exception)
        {
            info("DAILY DATA FETCH COMMAND EXCEPTION:" . $exception->getMessage());
        }
    }
}
