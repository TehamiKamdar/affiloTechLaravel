<?php

namespace App\Jobs;

use App\Helper\Static\Methods;
use App\Models\NetworkFetchData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NetworkAdvertiserExtraFetchStatusUpdateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $source, $jobID, $isStatusChange;

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
        $this->source = $data['source'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            NetworkFetchData::where("name", $this->source)->update([
                "advertiser_extra_schedule_status" => NetworkFetchData::COMPLETED,
                "last_updated_advertiser_extra" => now()->format("Y-m-d")
            ]);

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Exception $exception)
        {
            Methods::catchBodyFetchDaily(ucwords($this->source) . " NETWORK ADVERTISER EXTRA FETCH STATUS UPDATE JOB", $this->jobID, $this->isStatusChange);
        }
    }
}
