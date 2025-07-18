<?php

namespace App\Jobs;

use App\Helper\LinkGenerate;
use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Sync\LinkJob;
use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use App\Models\FetchDailyData;
use App\Models\GenerateLink;
use App\Models\Tracking;
use App\Traits\RequestTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateTrackingLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
                    $applyAdvertiser = AdvertiserPublisher::where("publisher_id", $this->publisherId)->where("website_id", $this->websiteId)->where('advertiser_id', $this->advertiser->id)->first();
                    if(isset($applyAdvertiser->advertiser_id))
                    {
                        $applyAdvertiser->update([
                            "click_through_url" => $link,
                            'is_tracking_generate' => AdvertiserPublisher::GENERATE_LINK_COMPLETE
                        ]);

                        Tracking::updateOrCreate([
                            'advertiser_id' => $applyAdvertiser->advertiser_id,
                            'website_id' => $applyAdvertiser->website_id,
                            'publisher_id' => $applyAdvertiser->publisher_id,
                            'sub_id' => $this->subId
                        ], [
                            "click_through_url" => $link,
                        ]);
                    }
                    else
                    {
                        Tracking::where("publisher_id", $this->publisherId)->where("website_id", $this->websiteId)->where('advertiser_id', $this->advertiser->id)->delete();
                        $id = $this->advertiser->id ?? null;
                        $arr = ["ADVERTISER ID: {$id}", "PUBLISHER ID: {$this->publisherId}", "WEBSITE: {$this->websiteId}"];
                        info(json_encode($arr));
                    }

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
