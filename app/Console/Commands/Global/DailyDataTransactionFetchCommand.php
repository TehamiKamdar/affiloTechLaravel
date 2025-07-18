<?php

namespace App\Console\Commands\Global;

use App\Helper\Static\Vars;
use App\Helper\Static\Methods;
use App\Jobs\Sync\TransactionJob as SyncTransactionJob;
use App\Models\FetchDailyData;
use App\Models\Setting;
use Illuminate\Console\Command;

class DailyDataTransactionFetchCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-data-transaction-fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily Data Transaction Fetch the API Server through command.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            $settings = Setting::select('value')
                ->where('key', 'daily_fetch')
                ->first();

            $dataSyncCheck = \App\Models\SyncData::where('is_sync', 0)->count();


            // Check if Awin is being processed
            $run = FetchDailyData::select('source')->where('is_processing', Vars::JOB_IN_PROCESS)
                                        ->where('is_processing', Vars::JOB_IN_PROCESS)
                                        ->groupBy('source')
                                        ->get()->pluck('source')->toArray();
            
                // Check if there are any active TRANSACTION_SHORT jobs that need processing
                $checkShortTransactions = FetchDailyData::select('id')
                    ->where('status', Vars::JOB_ACTIVE)
                    ->whereIn('is_processing', [Vars::JOB_NOT_PROCESS, Vars::JOB_ERROR])
                    ->where('type', Vars::TRANSACTION_SHORT)
                    ->count();

                // Set the job type to prioritize short transactions if any exist
                $type = Vars::TRANSACTION;
                if ($checkShortTransactions > 0) {
                    $type = Vars::TRANSACTION_SHORT;
                }

                // Define the sources to check, skipping others if Awin is processing
                $list = Vars::OPTION_LIST;

                $sources = array_merge($list, [Vars::ADMIN_WORK, Vars::GLOBAL, Vars::TRADEDOUBLER]);
               


                // Fetch jobs based on the determined type and source
                $jobs = FetchDailyData::where('type', $type)
                    ->where('status', Vars::JOB_ACTIVE)
                    ->whereIn('is_processing', [Vars::JOB_NOT_PROCESS, Vars::JOB_ERROR])
                    ->whereIn('source', $sources)
                    ->orderBy('process_date', 'ASC')
                    ->get();

                if (count($jobs)) {
                    // Get the IDs of the jobs to update
                    $jobIds = $jobs->pluck('id')->toArray();

                    FetchDailyData::whereIn('id', $jobIds)->update([
                        'is_processing' => Vars::JOB_IN_PROCESS
                    ]);

                    foreach ($jobs as $job) {
                        $payload = json_decode($job->payload, true);
                        $payload['job_id'] = $job->id;
                        $payload['is_status_change'] = true;

                        $queue = $job->queue;
                        SyncTransactionJob::dispatch($payload)->onQueue($queue);
                    }
                }
           

            if ($settings->value == 1) {
                $jobs = FetchDailyData::whereIn('is_processing', [Vars::JOB_NOT_PROCESS, Vars::JOB_IN_PROCESS, Vars::JOB_ERROR])
                    ->count();

                if ($jobs == 0) {
                    FetchDailyData::updateOrCreate([
                        'path' => 'SyncStatusChangeJob',
                        'process_date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                        'key' => 0,
                        'queue' => Vars::ADMIN_WORK,
                        'source' => Vars::GLOBAL,
                        "type" => Vars::GLOBAL
                    ], [
                        'name' => 'Sync Status Change Job for API Server',
                        'payload' => json_encode([
                            'status' => 0,
                        ]),
                        'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    ]);

                    Setting::updateOrCreate([
                        'key' => 'daily_fetch'
                    ], [
                        'value' => 0,
                    ]);
                }
            }

        } catch (\Exception $exception) {
            info("DAILY DATA FETCH COMMAND EXCEPTION: " . $exception->getMessage());
        }
    }
}
