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

class ManualApprovalNetworkActiveAdvertiser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $ids, $jobID, $isStatusChange;

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
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {

            Methods::customDefault("MANUAL APPROVAL NETWORK ACTIVE ADVERTISER",  "IDS MANUAL APPROVAL NETWORK ACTIVE ADVERTISER FETCHING START");

            $advertisers = Advertiser::whereIn("id", $this->ids);

            if($advertisers->count())
            {
                $advertisers->update([
                    'status' => Vars::ADVERTISER_STATUS_ACTIVE
                ]);

                UpdateAdvertiserStatusOnFetchTimeJobByID::dispatch([
                    "ids" => $this->ids,
                    "status" => AdvertiserPublisher::STATUS_PENDING,
                ])->onQueue(Vars::ADMIN_WORK);
            }

            Methods::customDefault(null, "IDS MANUAL APPROVAL NETWORK ACTIVE ADVERTISER FETCHING END");
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Exception $exception) {

            Methods::customError("ADVERTISER DELETE TO FROM NETWORK", $exception);
            Methods::catchBodyFetchDaily("IDS MANUAL APPROVAL NETWORK ACTIVE ADVERTISER", $this->jobID, $this->isStatusChange);

        }
    }
}
