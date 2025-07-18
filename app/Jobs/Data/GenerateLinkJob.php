<?php
namespace App\Jobs\Data;

use App\Helper\DeeplinkGenerate;
use App\Helper\LinkGenerate;
use App\Helper\Methods;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\TrackingUrl;
use App\Services\Publisher\RootService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Request;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class GenerateLinkJob implements ShouldQueue
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

    public function handle(Request $request) : void
    {
        try {
            // Fetch the advertiser record using the provided advertiser ID
            $advertiser = Advertiser::where("id", $this->data["advertiser_id"])->first();

            // Generate the correct link based on the type of link (TrackingUrl::LINK or Deeplink)
            if ($this->data["type"] === TrackingUrl::LINK) {
                $linkGenerator = new LinkGenerate();
                $link = $linkGenerator->generate(
                    $advertiser, 
                    $this->data["publisher_id"], 
                    $this->data["website_id"], 
                    $this->data["sub_id"]
                );
            } else {
                $linkGenerator = new DeeplinkGenerate();
                $link = $linkGenerator->generate(
                    $advertiser, 
                    $this->data["publisher_id"], 
                    $this->data["website_id"], 
                    $this->data["sub_id"], 
                    $this->data["landing_url"] ?? null
                );
            }

            // Merge the generated data into the request
            $request->merge([
                "advertiser_id" => $this->data["advertiser_id"],
                "advertiser_sid" => $advertiser->sid,
                "publisher_id" => $this->data["publisher_id"],
                "website_id" => $this->data["website_id"],
                "sub_id" => $this->data["sub_id"],
                "landing_url" => $this->data["landing_url"] ?? null,
                "original_url" => $link
            ]);

            // Call the RootService to process the tracking URL
            $service = new RootService();

            if (!isset($this->data["landing_url"])) {
                // If no landing URL is set, proceed with creating the tracking URL
                $data = $service->makeTrackingURL($request);

                // Update the AdvertiserPublisher with the new tracking URL details
                AdvertiserPublisher::where("id", $this->data["advertiser_publisher_id"])
                    ->update([
                        "tracking_url" => $data["tracking_url"],
                        "tracking_url_url" => $data["url"],
                        "click_through_url" => $link
                    ]);
            }

            // Call method to attempt body fetch for the daily task
            Methods::tryBodyFetchDaily($this->jobID, $this->isStatusChange);

        } catch (\Throwable $exception) {
            // Log any exceptions encountered during the process
            Methods::catchBodyFetchDaily("GENERATE LINK JOB", $exception, $this->jobID);
        }
    }
}
