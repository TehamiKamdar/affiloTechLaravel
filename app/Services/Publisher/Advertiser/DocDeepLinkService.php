<?php

namespace App\Services\Publisher\Advertiser;

use App\Classes\RandomStringGenerator;
use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Http\Requests\Doc\DeeplinkRequest;
use App\Jobs\Sync\LinkJob;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\DeeplinkTracking;
use App\Models\FetchDailyData;
use App\Models\GenerateLink as GenerateLinkModel;
use App\Models\Tracking;
use App\Models\Website;
use App\Traits\DeepLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class DocDeepLinkService
{

    use DeepLink;

    public function checkAvailability(DeeplinkRequest $request, $user, $websiteId)
    {
        $advertiser = Advertiser::select('id', 'sid', 'name', 'deeplink_enabled', 'advertiser_id', 'source', 'click_through_url', 'url')->where("sid", $request->widgetAdvertiser)->first();
        $apply = AdvertiserPublisher::select('id')->where("advertiser_sid", $request->widgetAdvertiser)->where("publisher_id", $user->id)->where("website_id", $websiteId)->first();

        $deepLinkEnable = $advertiser->deeplink_enabled ?? false;

        if($advertiser && isset($apply->id) && $deepLinkEnable && $request->landing_url) {

            return $this->createNGetDeeplink($request, $advertiser, $websiteId, $user);

        } else {

            return response()->json([
                "success" => false,
                "message" => "This advertiser is not allowed to create deeplinks."
            ]);

        }
    }

    private function createNGetDeeplink(DeeplinkRequest $request, Advertiser $advertiser, $websiteId, $user)
    {
        $subID = $request->sub_id ?? null;
        $url = parse_url($request->landing_url);
        $landing_url = $url['scheme'] . "://" . $url['host'] . ($url["path"] ?? "");

        $cacheKey = 'deeplink_create_n_get_' . $advertiser->id . '_' . $websiteId . '_' . $user->id . '_' . $landing_url . '_' . $subID;
        $deeplink = Cache::get($cacheKey);

        if (!$deeplink) {
            $deeplink = DeeplinkTracking::select([
                "click_through_url",
                "tracking_url",
                "tracking_url_long",
                "advertiser_id",
                "publisher_id",
                "website_id",
                "landing_url",
                "sub_id",
                "id"
            ])->where([
                "advertiser_id" => $advertiser->id,
                "website_id" => $websiteId,
                "publisher_id" => $user->id,
                "landing_url" => $landing_url,
                "sub_id" => $subID
            ])->first();

            if ($deeplink && $deeplink->click_through_url) {
                Cache::forever($cacheKey, $deeplink);
            }
        }

        if(!isset($deeplink->id))
        {
            $website = Website::select('wid')->where("id", $websiteId)->first();
            $shortLink = $this->generateShortLink();
            $longLink = $this->generateLongLink($advertiser->sid, $website->wid, $subID, $landing_url);

            $deeplink = DeeplinkTracking::create([
                "advertiser_id" => $advertiser->id,
                "website_id" => $websiteId,
                "publisher_id" => $user->id,
                "landing_url" => $landing_url,
                "sub_id" => $subID,
                "click_through_url" => '',
                "tracking_url" => $shortLink,
                "tracking_url_long" => $longLink
            ]);

        }
        else
        {
            $shortLink = $deeplink->tracking_url;
            $longLink = $deeplink->tracking_url_long;
        }

        if (!$deeplink->click_through_url) {

            GenerateLinkModel::updateOrCreate([
                'advertiser_id' => $deeplink->advertiser_id,
                'publisher_id' => $deeplink->publisher_id,
                'website_id' => $deeplink->website_id,
                'landing_url' => $deeplink->landing_url,
                'sub_id' => $deeplink->sub_id
            ], [
                'name' => 'Deep Link Job',
                'path' => 'DeeplinkGenerateJob',
                'payload' => collect($deeplink->toArray()),
                'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                'queue' => Vars::LINK_GENERATE
            ]);
        }

        return response()->json([
            "success" => true,
            "name" => $advertiser->name ?? null,
            "deeplink_enabled" => true,
            "deeplink_link_short_url" => $shortLink,
            "deeplink_link_url" => $longLink
        ]);

    }

    private function getTrackingLink(Request $request, Advertiser $advertiser, AdvertiserPublisher $apply, $user)
    {
        $url = parse_url($request->landing_url);
        $landing_url = $url['scheme'] . "://" . $url['host'] . $url["path"];

        if($request->sub_id)
        {

            $cacheKey = 'get_tracking_url_' . $request->sub_id . '_' . $advertiser->id . '_' . $apply->publisher_id . '_' . $apply->website_id;
            $tracking = Cache::get($cacheKey);

            if (!$tracking) {

                $tracking = Tracking::select('tracking_url_short')->where([
                    "sub_id" => $request->sub_id,
                    'advertiser_id' => $advertiser->id,
                    'publisher_id' => $apply->publisher_id,
                    'website_id' => $apply->website_id
                ])
                    ->first();

                if ($tracking && $tracking->click_through_url) {
                    Cache::forever($cacheKey, $tracking);
                }
            }

            if($tracking)
            {

                $track = $tracking->tracking_url_short ?? null;

            }
            else{
                $queue = Vars::LINK_GENERATE;
                GenerateLinkModel::updateOrCreate([
                    'advertiser_id' => $advertiser->id,
                    'publisher_id' => $apply->publisher_id,
                    'website_id' => $apply->website_id,
                    'landing_url' => $landing_url,
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

                $shortLink = $this->generateTrackingShortLink();

                $link = Tracking::updateOrCreate(
                    [
                    "sub_id" => $request->sub_id,
                    'advertiser_id' => $advertiser->id,
                    'publisher_id' => $user->id,
                    'website_id' => $apply->website_id
                ], [
                    "click_through_url" => '',
                    "tracking_url_short" => $shortLink,
                    "is_tracking_generate" => AdvertiserPublisher::GENERATE_LINK_IN_PROCESS
                ]);

                $track = $link->tracking_url_short ?? null;

            }
        }
        else
        {
            if(isset($apply->tracking_url_short) && isset($apply->is_tracking_generate) && $apply->is_tracking_generate == AdvertiserPublisher::GENERATE_LINK_COMPLETE)
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

    public function generateTrackingShortLink()
    {
        $generator = new RandomStringGenerator();
        $tokenLength = 8;
        $code = $generator->generate($tokenLength);
        $trackCode = Tracking::select('id')->where("tracking_url_short", route("track.simple.short", ['code' => $code]))->count();
        if($trackCode > 0)
        {
            return $this->generateTrackingShortLink();
        }
        return route("track.simple.short", ['code' => $code]);
    }

    private function makeParseURL($url)
    {
        $url = parse_url($url);
        $landing_url = $url['scheme'] . "://" . $url['host'];

        if(isset($url["path"]))
            $landing_url = $landing_url . $url["path"];

        return $landing_url;
    }
}
