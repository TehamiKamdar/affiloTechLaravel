<?php
namespace App\Jobs\Data;

use App\Helper\Methods;
use App\Helper\Vars;
use App\Models\AdvertiserPublisher;
use App\Models\FetchDailyData;
use App\Models\TrackingUrl;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\GenerateLink as GenerateLinkModel;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Advertiser;
use App\Models\AdvertiserApply;
use App\Models\Tracking;
use App\Models\User;
use App\Models\Website;
use App\Traits\GenerateLink;
use App\Traits\Notification\Advertiser\Approval;
use App\Traits\Notification\Advertiser\JoinedAdvertiserHold;
use App\Traits\Notification\Advertiser\JoinedAdvertiserReject;
use App\Traits\Notification\Advertiser\Reject;


class ApplyAdvertiserJob implements ShouldQueue
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
            $advertiserPublisher = AdvertiserPublisher::where("id", $this->data["id"])->first();

            if (isset($advertiserPublisher->id)) {
                // Update status for the advertiser
                $advertiserPublisher->update([
                    "status" => $this->data["status"]
                ]);

                // If the click-through URL is empty and the status is active, create a new FetchDailyData record
                if (empty($advertiserPublisher->click_through_url) && $this->data["status"] == AdvertiserPublisher::STATUS_ACTIVE) {
                    FetchDailyData::updateOrCreate(
                        [
                            "path" => "DataGeneratorLinkJob",
                            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                            "key" => $advertiserPublisher->id,
                            "advertiser_id" => $this->data["advertiser_id"],
                            "publisher_id" => $this->data["publisher_id"],
                            "website_id" => $this->data["website_id"],
                            "sub_id" => $this->data["sub_id"] ?? null,
                            "queue" => Vars::EXTRA_WORK,
                            "source" => $this->data["source"],
                            "type" => Vars::ADVERTISER,
                            "date" => now()->toDateString()
                        ],
                        [
                            "name" => "Generator Link Job",
                            "payload" => json_encode([
                                "source" => $this->data["source"],
                                "advertiser_id" => $this->data["advertiser_id"],
                                "publisher_id" => $this->data["publisher_id"],
                                "website_id" => $this->data["website_id"],
                                "sub_id" => $this->data["sub_id"] ?? null,
                                "advertiser_publisher_id" => $advertiserPublisher->id,
                                "type" => TrackingUrl::LINK
                            ]),
                            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2)
                        ]
                    );
                }
                
                 $queue = Vars::LINK_GENERATE;
            GenerateLinkModel::updateOrCreate([
                'advertiser_id' => $advertiserPublisher->advertiser->id,
                'publisher_id' => $advertiserPublisher->publisher_id,
                'website_id' => $advertiserPublisher->website_id,
                'sub_id' => null
            ],[
                'name' => 'Tracking Link Job',
                'path' => 'GenerateTrackingLinkJob',
                'payload' => collect([
                    'advertiser' => $advertiserPublisher->advertiser,
                    'publisher_id' => $advertiserPublisher->publisher_id,
                    'website_id' => $advertiserPublisher->website_id
                ]),
                'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                'queue' => $queue
            ]);
            
            $trackURL = $shortURL = $longURL = null;
                $linkGenerate = AdvertiserPublisher::GENERATE_LINK_EMPTY;
                if($this->data["status"] == AdvertiserPublisher::STATUS_ACTIVE)
                {
                    $generate = $this->adminGenerateLink($advertiserPublisher);
                    $trackURL = $generate['track_url'];
                    $longURL = $generate['long_url'];
                    $shortURL = $generate['short_url'];
                    $linkGenerate = $generate['link_generate'];
                    $this->approvalNotification($advertiserPublisher);
                }
                elseif ($this->data["status"] == AdvertiserPublisher::STATUS_HOLD && $advertiserPublisher->status == AdvertiserPublisher::STATUS_ACTIVE)
                {
                    $this->joinedAdvertiserHoldNotification($advertiserPublisher);
                }
                elseif ($this->data["status"] == AdvertiserPublisher::STATUS_REJECTED && $advertiserPublisher->status == AdvertiserPublisher::STATUS_ACTIVE)
                {
                    $this->joinedAdvertiserRejectNotification($advertiserPublisher);
                }
                elseif ($this->data["status"] == AdvertiserPublisher::STATUS_REJECTED)
                {
                    $this->rejectNotification($advertiserPublisher);
                }

                $advertiserPublisher->update([
                    'approver_id' => auth()->user()->id,
                    'reject_approve_reason' => $request['message'] ?? null,
                    'status' => $this->data["status"],
                    'tracking_url' => $trackURL,
                    'tracking_url_short' => $shortURL,
                    'tracking_url_long' => $longURL,
                    'is_tracking_generate' => $linkGenerate
                ]);

            }

            // Attempt body fetch for the daily task
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Throwable $exception) {
            // Log any exceptions encountered during the process
            Methods::catchBodyFetchDaily("APPLY ADVERTISER JOB", $exception, $this->jobID);
        }
    }
    
     private function adminGenerateLink($advertiser)
    {
        $website = Website::select('wid')->where("id", $advertiser->website_id)->first();

        if($advertiser->tracking_url)
            $trackURL = $advertiser->tracking_url;
        else
            $trackURL = $this->generateLink($advertiser->advertiser_id, $advertiser->website_id);

        if($advertiser->tracking_url_short)
            $shortURL = $advertiser->tracking_url_short;
        else
            $shortURL = $this->generateShortLink();

        if($advertiser->tracking_url_long)
            $longURL = $advertiser->tracking_url_long;
        else
            $longURL = $this->generateLongLink($advertiser->advertiser_sid, $website->wid, null);

        $linkGenerate = AdvertiserPublisher::GENERATE_LINK_IN_PROCESS;

        $advertiserCollection = $advertiser->advertiser;

        if(isset($advertiserCollection->id))
        {
            $queue = Vars::LINK_GENERATE;
            GenerateLinkModel::updateOrCreate([
                'advertiser_id' => $advertiser->advertiser->id,
                'publisher_id' => $advertiser->publisher_id,
                'website_id' => $advertiser->website_id,
                'sub_id' => null
            ],[
                'name' => 'Tracking Link Job',
                'path' => 'GenerateTrackingLinkJob',
                'payload' => collect([
                    'advertiser' => $advertiserCollection,
                    'publisher_id' => $advertiser->publisher_id,
                    'website_id' => $advertiser->website_id
                ]),
                'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                'queue' => $queue
            ]);
        }

//        GenerateTrackingLinkJob::dispatch($genericAdv, $advertiser->publisher_id, $advertiser->website_id)->onQueue($queue);

        return [
            "track_url" => $trackURL,
            "short_url" => $shortURL,
            "long_url" => $longURL,
            "link_generate" => $linkGenerate
        ];
    }
}
