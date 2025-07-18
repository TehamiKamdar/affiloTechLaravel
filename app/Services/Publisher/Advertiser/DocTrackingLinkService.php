<?php

namespace App\Services\Publisher\Advertiser;

use App\Classes\RandomStringGenerator;
use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Http\Requests\Doc\DeeplinkRequest;
use App\Http\Requests\Doc\TrackingLinkRequest;
use App\Jobs\Sync\LinkJob;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\DeeplinkTracking;
use App\Models\FetchDailyData;
use App\Models\GenerateLink as GenerateLinkModel;
use App\Models\Tracking;
use App\Models\Website;
use App\Traits\DeepLink;
use App\Traits\GenerateLink;
use Illuminate\Http\Request;

class DocTrackingLinkService
{

    use GenerateLink;

    public function checkAvailability(TrackingLinkRequest $request, $user, $websiteId)
    {
        $advertiser = Advertiser::select('id', 'sid', 'name', 'deeplink_enabled', 'advertiser_id', 'source', 'click_through_url', 'url')->where("sid", $request->widgetAdvertiser)->first();
        $apply = AdvertiserPublisher::where("advertiser_sid", $request->widgetAdvertiser)->where("publisher_id", $user->id)->where("website_id", $websiteId)->first();

        if($apply && $advertiser)
            return $this->getTrackingLink($request, $advertiser, $apply, $user);

        return response()->json([
            "success" => false,
            "name" => $advertiser->name ?? null,
            "tracking_url" => $track ?? null
        ], 404);

    }

    private function getTrackingLink(Request $request, Advertiser $advertiser, AdvertiserPublisher $apply, $user)
    {

        if($request->sub_id)
        {
            $tracking = Tracking::select(['tracking_url_long','tracking_url_short'])->where([
                "sub_id" => $request->sub_id,
                'advertiser_id' => $advertiser->id,
                'publisher_id' => $apply->publisher_id,
                'website_id' => $apply->website_id
            ])
                ->first();

            if($tracking)
            {

                $track = $tracking->tracking_url_long ?? $tracking->tracking_url_short ?? null;

            }
            else{
                $queue = Vars::LINK_GENERATE;
                GenerateLinkModel::updateOrCreate([
                    'advertiser_id' => $advertiser->id,
                    'publisher_id' => $apply->publisher_id,
                    'website_id' => $apply->website_id,
                    'sub_id' => $request->sub_id
                ],[
                    'name' => 'Tracking Link Job With Sub ID',
                    'path' => 'GenerateTrackingLinkWithSubIDJob',
                    'payload' => collect([
                        'advertiser' => $advertiser,
                        'publisher_id' => $advertiser->publisher_id,
                        'website_id' => $advertiser->website_id
                    ]),
                    'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    'queue' => $queue
                ]);

                $website = Website::select('wid')->where("id", $apply->website_id)->first();

                $shortLink = $this->generateShortLink();
                $longURL = $this->generateLongLink($advertiser->sid, $website->wid, $request->sub_id);

                $link = Tracking::updateOrCreate(
                    [
                        "sub_id" => $request->sub_id,
                        'advertiser_id' => $advertiser->id,
                        'publisher_id' => $user->id,
                        'website_id' => $apply->website_id
                    ],
                    [
                        "click_through_url" => '',
                        "tracking_url_short" => $shortLink,
                        "tracking_url_long" => $longURL,
                        "is_tracking_generate" => AdvertiserPublisher::GENERATE_LINK_IN_PROCESS
                    ]
                );

                $track = $link->tracking_url_long ?? $link->tracking_url_short ?? null;
            }
        }
        else
        {
            if(isset($apply->tracking_url_long) && isset($apply->is_tracking_generate) && $apply->is_tracking_generate == AdvertiserPublisher::GENERATE_LINK_COMPLETE)
                $track = $apply->tracking_url_long;

            elseif(isset($apply->tracking_url_short) && isset($apply->is_tracking_generate) && $apply->is_tracking_generate == AdvertiserPublisher::GENERATE_LINK_COMPLETE)
                $track = $apply->tracking_url_short;

            elseif(isset($apply->is_tracking_generate) && $apply->is_tracking_generate == AdvertiserPublisher::GENERATE_LINK_IN_PROCESS)
                $track = "Generating tracking links.....";
        }

        return response()->json([
            "success" => true,
            "name" => $advertiser->name ?? null,
            "tracking_url" => $track ?? null
        ]);

    }

}
