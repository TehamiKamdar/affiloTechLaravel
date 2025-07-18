<?php

namespace App\Jobs;

use App\Helper\LinkGenerate;
use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Sync\LinkJob;
use App\Models\AdvertiserApply;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use App\Models\Tracking;
use App\Traits\Notification\Advertiser\Apply;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Traits\GenerateLink as GenerateLinkTrait;

class GenerateTrackingLinkWithSubIDJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, Apply, GenerateLinkTrait;

    private $jobID, $advertiser, $publisherId, $websiteId, $subId, $isStatusChange;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($jobID, $advertiser, $publisherId, $websiteId, $subId = null, $isStatusChange = false)
    {
        $this->jobID = $jobID;
        $this->advertiser = $advertiser;
        $this->publisherId = $publisherId;
        $this->websiteId = $websiteId;
        $this->subId = $subId;
        $this->isStatusChange = $isStatusChange;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            if($this->advertiser && $this->publisherId && $this->websiteId) {
                $link = new LinkGenerate();
                $link = $link->generate($this->advertiser, $this->publisherId, $this->websiteId, $this->subId);

                if(is_array($link)) {

                    GenerateLink::where("id", $this->jobID)->update([
                        "error_code" => $link['error_code'],
                        "error_message" => $link['error_message'],
                        'date' => $link['date'],
                        'is_processing' => Vars::JOB_ERROR
                    ]);

                } else {

                    $tracking = Tracking::updateOrCreate([
                        'advertiser_id' => $this->advertiser->id,
                        'website_id' => $this->websiteId,
                        'publisher_id' => $this->publisherId,
                        "sub_id" => $this->subId
                    ], [
                        "click_through_url" => $link,
                    ]);

//                    if($tracking->wasRecentlyCreated){
//                        FetchDailyData::updateOrCreate([
//                            "path" => "SyncLinkJob",
//                            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
//                            "queue" => Vars::ADMIN_WORK,
//                            "source" => Vars::GLOBAL,
//                            "key" => $tracking->id,
//                            "type" => Vars::ADVERTISER
//                        ], [
//                            "name" => "Link Syncing",
//                            "payload" => json_encode([
//                                'type' => 'tracking',
//                                'id' => $tracking->id
//                            ]),
//                            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
//                        ]);
//                    }

                    $this->applyNotification($this->advertiser, $this->publisherId);
                }

                GenerateLink::where("id", $this->jobID)->update([
                    'status' => Vars::JOB_STATUS_COMPLETE,
                    'is_processing' => Vars::JOB_ACTIVE
                ]);
            }
        } catch (\Throwable $exception) {

            Methods::customError("TRACKING LINK GENERATE JOB", $exception);

            $errorCode = $exception->getCode();
            $errorMessage = $exception->getMessage();
            $retryDateTime = now()->addHours(1)->format(Vars::CUSTOM_DATE_FORMAT_2);

            GenerateLink::where("id", $this->jobID)->update([
                "error_code" => $errorCode,
                "error_message" => $errorMessage,
                'date' => $retryDateTime,
                'is_processing' => Vars::JOB_ERROR
            ]);

        }

    }
}
