<?php

namespace App\Jobs\Sync;

use App\Helper\Static\Methods;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class AdvertiserStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data, $jobID, $isStatusChange;

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
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        info(json_encode($this->data));
        try {
            $url = env("APP_SERVER_API_URL");
            Http::post("{$url}api/sync-advertiser-status", $this->data);
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);
        } catch (\Exception $exception)
        {
            Methods::catchBodyFetchDaily("SYNC ADVERTISER STATUS JOB", $exception, $this->jobID);
        }
    }
}
