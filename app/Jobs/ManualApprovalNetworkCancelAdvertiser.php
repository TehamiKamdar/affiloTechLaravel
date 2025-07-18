<?php

namespace App\Jobs;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Models\Advertiser;
use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ManualApprovalNetworkCancelAdvertiser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ids, $type, $jobID, $isStatusChange;

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
        $this->ids = $data['ids'];
        $this->type = $data['type'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            Methods::customDefault("MANUAL APPROVAL NETWORK CANCEL ADVERTISER",  "IDS MANUAL APPROVAL NETWORK CANCEL ADVERTISER FETCHING START");

            $advertisers = Advertiser::whereIn("id", $this->ids);

            if($advertisers->count())
            {
                if($this->type == AdvertiserPublisher::STATUS_HOLD_CANCEL)
                {
                    $advertisers->update([
                        'is_available' => Vars::ADVERTISER_AVAILABLE,
                        "is_request__process" => 0
                    ]);
                }
                elseif ($this->type == AdvertiserPublisher::STATUS_ACTIVE_CANCEL)
                {
                    $advertisers->update([
                        'is_available' => Vars::ADVERTISER_NOT_AVAILABLE,
                        "is_request__process" => 0
                    ]);
                }
            }

            Methods::customDefault(null, "IDS MANUAL APPROVAL NETWORK CANCEL ADVERTISER FETCHING END");
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Exception $exception) {

            Methods::customError("MANUAL APPROVAL NETWORK CANCEL ADVERTISER", $exception);
            Methods::catchBodyFetchDaily("IDS MANUAL APPROVAL NETWORK CANCEL ADVERTISER", $this->jobID, $this->isStatusChange);

        }
    }
}
