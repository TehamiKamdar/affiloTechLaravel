<?php

namespace App\Jobs\Sync;

use App\Helper\Static\Methods;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class StatusChangeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $status = 0, $jobID, $isStatusChange;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->jobID = $data['job_id'];
        $this->isStatusChange = $data['is_status_change'];
        unset($data['job_id']);
        unset($data['is_status_change']);
        $this->status = $data['status'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $url = env("APP_SERVER_API_URL");
            Http::post("{$url}api/sync-status-change", [
                'status' => $this->status
            ]);
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);
        } catch (\Exception $exception)
        {
            Methods::catchBodyFetchDaily("SYNC STATUS CHANGE JOB", $exception, $this->jobID);
        }
    }
}
