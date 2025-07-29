<?php
namespace App\Console\Commands;

use App\Helper\Vars;
use App\Jobs\Export\AdvertiserExport as GenerateAdvertiserExportRequestJob;
use App\Jobs\Export\TransactionExport as GenerateTransactionExportRequestJob;
use App\Models\GenerateExportRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class GenerateExportCommand extends Command
{
    protected $signature = "app:generate-export-export";
    protected $description = "Generates a requested file in batches and shows progress";

    public function handle()
    {
        try {

            if ($this->shouldFetchData()) {
                $fetchDailyData = GenerateExportRequest::select("source")
                    ->where("is_processing", Vars::JOB_NOT_PROCESS)
                    ->where("status", Vars::JOB_STATUS_IN_PROCESS)
                    ->groupBy("source")
                    ->get();
            $this->info($fetchDailyData->count());
                foreach ($fetchDailyData as $key => $source) {

                    $jobs = $this->getJobsForSource($source->source);
                    $this->info($jobs);

                    if ($jobs->isNotEmpty()) {
                        $this->processJobs($jobs, $fetchDailyData->count(), $key);
                    }
                }
            }
        } catch (\Exception $exception) {
            info("DAILY DATA FETCH COMMAN ERROR: " . $exception->getMessage());
        }
    }

    private function shouldFetchData(): bool
    {
        $jobCheck = GenerateExportRequest::where("is_processing", Vars::JOB_IN_PROCESS)->count();
        return $jobCheck == 0;
    }

    private function getJobsForSource(string $source)
    {
        return DB::table("generate_export_requests")
            ->select(["id", "payload", "is_processing", "queue", "path"])
            ->where("status", Vars::JOB_ACTIVE)
            ->where("source", $source)
            ->whereIn("is_processing", [Vars::JOB_NOT_PROCESS, Vars::JOB_ERROR])
            ->orderBy("date", "ASC")
            ->take(20)
            ->get();

    }

    private function processJobs($jobs, int $totalSources, int $currentIndex)
    {
        $jobIds = $jobs->pluck("id")->toArray();
        GenerateExportRequest::whereIn("id", $jobIds)->update([
            "is_processing" => Vars::JOB_IN_PROCESS
        ]);

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
            "GenerateAdvertiserExportRequestJob" => GenerateAdvertiserExportRequestJob::class,
            "GenerateTransactionExportRequestJob" => GenerateTransactionExportRequestJob::class
        ];

        if (isset($jobClasses[$path])) {
            $jobClasses[$path]::dispatch($payload)->onQueue($queue);
        }
    }
}
