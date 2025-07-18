<?php

namespace App\Console\Commands\Global;

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
use App\Jobs\Sync\AdvertiserStatusJob as SyncAdvertiserStatusJob;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use App\Models\History;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DailyMakeHistoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-make-history';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Maintain the History through command.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try
        {

            $jobCheck = History::select('id')->where('is_processing', 0)->count();
            $setting = Setting::where('key', 'is_processing_tracking_deeplinks')->where('value', false)->first();

            if($jobCheck > 0)// && count($result) == 0)
            {
                Setting::where('key', 'make_history_running')->update([
                    'value' => true
                ]);

                $jobs = DB::table('histories')
                    ->select(['id', 'payload', 'is_processing', 'queue', 'path'])
                    ->where('status', Vars::JOB_ACTIVE)
                    ->whereIn('is_processing', [Vars::JOB_NOT_PROCESS, Vars::JOB_ERROR])
                    ->orderBy('date', 'ASC')
                    ->orderBy('sort', 'ASC')
                    ->take(Vars::LIMIT_50)
                    ->get();

                if(count($jobs))
                {

                    // Get the IDs of the jobs to update
                    $jobIds = $jobs->pluck('id')->toArray();

                    History::whereIn('id', $jobIds)->update([
                        'is_processing' => Vars::JOB_IN_PROCESS
                    ]);

                    foreach ($jobs as $job)
                    {

                        $payload = json_decode($job->payload, true);
                        $payload['job_id'] = $job->id;

                        $payload['is_status_change'] = true;

                        $queue = $job->queue;
                        switch ($job->path)
                        {

                            case "MakeHistoryTrackingJob":
                            case "MakeHistoryDeeplinkTrackingJob":
                                MakeHistory::dispatch($payload)->onQueue($queue);
                                break;

                            default:
                                break;

                        }

                    }
                }

                $jobCheck = History::select('id')->where('is_processing', Vars::JOB_IN_PROCESS)->count();

                if($jobCheck == 0)
                {
                    Setting::where('key', 'make_history_running')->update([
                        'value' => false
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
