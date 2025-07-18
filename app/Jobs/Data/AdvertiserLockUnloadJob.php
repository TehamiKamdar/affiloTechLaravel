<?php
namespace App\Jobs\Data;

use App\Helper\Methods;
use App\Models\AdvertiserPublisher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AdvertiserLockUnloadJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    protected $data, $jobID, $isStatusChange;

    public function __construct($data)
    {
        $this->jobID = $data["job_id"];
        $this->isStatusChange = $data["is_status_change"];
        
        unset($data["job_id"]);
        unset($data["is_status_change"]);
        
        $this->data = $data;
    }

    public function handle() : void
    {
        try {
            AdvertiserPublisher::updateOrCreate(
                [
                    "advertiser_id" => $this->data["advertiser_id"],
                    "publisher_id" => $this->data["publisher_id"],
                    "website_id" => $this->data["website_id"],
                    "source" => $this->data["source"]
                ],
                [
                    "locked_status" => $this->data["status"],
                    "applied_at" => now()->toDateTimeString()
                ]
            );

            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Throwable $exception) {
            Methods::catchBodyFetchDaily("ADVERTISER LOCK UNLOCK JOB", $exception, $this->jobID);
        }
    }
}
