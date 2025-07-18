<?php
namespace App\Console\Commands;

use App\Helper\Vars;
use App\Jobs\Data\AdvertiserLockUnloadJob as DataAdvertiserLockUnloadJob;
use App\Jobs\Data\ApplyAdvertiserJob as DataAdvertiserPublisherStatucChange;
use App\Jobs\Data\GenerateLinkJob as DataGenerateLinkJob;
use App\Models\FetchDailyData;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DailyDataFetchCommand extends Command
{
    protected $signature = "app-daily-data-fetchss";
    protected $description = "Daily Data Fetch through API Server through command.";

    public function handle()
    {
        try {
            if ($this->shouldFetchData()) {
                $fetchDailyData = FetchDailyData::select("source")
                    ->where("is_processing", Vars::JOB_NOT_PROCESS)
                    ->where("status", Vars::JOB_STATUS_IN_PROCESS)
                    ->groupBy("source")
                    ->get();

                foreach ($fetchDailyData as $key => $source) {
                    $jobs = $this->getJobsForSource($source->source);
                    if ($jobs->isNotEmpty()) {
                        $this->processJobs($jobs, $fetchDailyData->count(), $key);
                    }
                }
            }
        } catch (\Exception $exception) {
            info("DAILY DATA FETCH COMMAND EXCEPTION: " . $exception->getMessage());
        }
    }

    private function shouldFetchData(): bool
    {
        $jobCheck = FetchDailyData::where("is_processing", Vars::JOB_IN_PROCESS)->count();
        return $jobCheck == 0;
    }

    private function getJobsForSource(string $source)
    {
        return DB::table("fetch_daily_data")
            ->select(["id", "payload", "is_processing", "queue", "path"])
            ->where("status", Vars::JOB_ACTIVE)
            ->where("source", $source)
            ->whereIn("is_processing", [Vars::JOB_NOT_PROCESS, Vars::JOB_ERROR])
            ->orderBy("date", "ASC")
            ->orderBy("sort", "ASC")
            ->take(20)
            ->get();
    }

    private function processJobs($jobs, int $totalSources, int $currentIndex)
    {
        $jobIds = $jobs->pluck("id")->toArray();
        FetchDailyData::whereIn("id", $jobIds)->update(["is_processing" => Vars::JOB_IN_PROCESS]);

        foreach ($jobs as $job) {
            $payload = json_decode($job->payload, true);
            $payload["job_id"] = $job->id;
            $payload["is_status_change"] = $totalSources == $currentIndex + 1;
            $this->dispatchJob($job->path, $payload, $job->queue);
        }
    }

    private function dispatchJob(string $path, array $payload, string $queue)
    {
        $jobClasses = [
            "DataAdvertiserLockUnloadJob" => DataAdvertiserLockUnloadJob::class,
            "DataAdvertiserPublisherStatucChange" => DataAdvertiserPublisherStatucChange::class,
            "DataGenerateLinkJob" => DataGenerateLinkJob::class
        ];

        if (isset($jobClasses[$path])) {
            $jobClasses[$path]::dispatch($payload)->onQueue($queue);
        }
    }
}
