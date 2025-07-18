<?php
namespace App\Services\Publisher\Promote;

use App\Helper\DeeplinkGenerate;
use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\MakeHistory;
use App\Jobs\Sync\LinkJob;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\DeeplinkTracking;
use App\Models\DeeplinkTrackingDetail;
use App\Models\DelDeeplinkTracking;
use App\Models\FetchDailyData;
use App\Models\History;
use App\Models\User;
use App\Models\Website;
use App\Traits\DeepLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class DeepLinkService
{
    use DeepLink;

    /**
     * @param Request $request
     * @return array|null
     */
    public function storeLongCode(Request $request): ?array
    {
        $ued = urldecode($request->ued);
        $url = route("track.deeplink.long", [
            "linkmid" => $request->linkmid,
            "linkaffid" => $request->linkaffid,
            "subid" => $request->subid,
            "ued" => $ued,
        ]);

        $cacheKey = 'deeplink_long_code_tracking_' . md5($url);
        $deeplinkTracking = Cache::get($cacheKey);

        if (!$deeplinkTracking) {
            $deeplinkTracking = DeeplinkTracking::with('advertiser')
                ->select('id', 'publisher_id', 'website_id', 'advertiser_id', 'click_through_url', 
                         'tracking_url', 'tracking_url_long', 'sub_id', 'hits', 'unique_visitor')
                ->where("tracking_url_long", $url)
                ->first();

            if (empty($deeplinkTracking)) {
                $deeplinkTracking = DelDeeplinkTracking::with('advertiser')
                    ->select('id', 'publisher_id', 'website_id', 'advertiser_id', 'click_through_url', 
                             'tracking_url', 'tracking_url_long', 'sub_id', 'hits', 'unique_visitor')
                    ->where("tracking_url_long", $url)
                    ->first();
            }

            if ($deeplinkTracking && $deeplinkTracking->click_through_url) {
                Cache::forever($cacheKey, $deeplinkTracking);
            }
        }

        if ($deeplinkTracking) {
            return $this->getClickThroughURL($request, $deeplinkTracking);
        }

        $advertiser = AdvertiserPublisher::where("advertiser_sid", $request->linkmid)
            ->where("website_wid", $request->linkaffid)
            ->where("status", AdvertiserPublisher::STATUS_ACTIVE)
            ->first();

        if (!$advertiser) {
            return [
                "success" => false,
                "view" => view("template.publisher.advertisers.deeplink-dead", compact('advertiser')),
            ];
        }

        $link = new DeeplinkGenerate();
        $clickLink = $link->generate(
            $advertiser->advertiser,
            $advertiser->publisher_id,
            $advertiser->website_id,
            $request->subid,
            $ued
        );

        if (!isset($deeplinkTracking->id)) {
            $deeplinkTracking = DeeplinkTracking::create([
                "advertiser_id" => $advertiser->internal_advertiser_id,
                "website_id" => $advertiser->website_id,
                "publisher_id" => $advertiser->publisher_id,
                "tracking_url_long" => $url,
                "sub_id" => $request->subid,
                "landing_url" => $ued,
                "click_through_url" => $clickLink,
            ]);
        }

        $deeplinkTracking->new_advertiser_id = $advertiser->id;

        $this->makeHistory($request, $deeplinkTracking);

        return [
            "success" => true,
            "url" => $deeplinkTracking->click_through_url ?? null,
        ];
    }

    /**
     * @param Request $request
     * @param string $code
     * @return array|null
     */
    public function storeCode(Request $request, string $code): ?array
    {
        $url = route("track.deeplink", $code);

        $cacheKey = 'deeplink_store_code_tracking_' . md5($url);
        $deeplinkTracking = Cache::get($cacheKey);

        if (!$deeplinkTracking) {
            $deeplinkTracking = DeeplinkTracking::with('advertiser')
                ->select([
                    'id', 'publisher_id', 'website_id', 'advertiser_id', 'click_through_url', 
                    'tracking_url', 'tracking_url_long', 'sub_id', 'hits', 'unique_visitor'
                ])
                ->where("tracking_url", $url)
                ->first();

            if (empty($deeplinkTracking)) {
                $deeplinkTracking = DelDeeplinkTracking::with('advertiser')
                    ->select([
                        'id', 'publisher_id', 'website_id', 'advertiser_id', 'click_through_url', 
                        'tracking_url', 'tracking_url_long', 'sub_id', 'hits', 'unique_visitor'
                    ])
                    ->where("tracking_url", $url)
                    ->first();
            }

            if ($deeplinkTracking && $deeplinkTracking->click_through_url) {
                Cache::forever($cacheKey, $deeplinkTracking);
            }
        }

        if ($deeplinkTracking) {
            return $this->getClickThroughURL($request, $deeplinkTracking);
        }

        return [
            "success" => false,
            "view" => view("template.publisher.advertisers.deeplink-dead"),
        ];
    }

    public function getClickThroughURL(Request $request, $deeplinkTracking)
    {
        // Method logic here
    }

    public function storeTrackingData(Request $request, $deeplinkTracking, $clickLink = null)
    {
        // Method logic here
    }
}
