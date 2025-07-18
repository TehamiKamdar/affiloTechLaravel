<?php
namespace App\Services\Publisher\Promote;

use App\Classes\RandomStringGenerator;
use App\Helper\LinkGenerate;
use App\Helper\Static\Vars;
use App\Jobs\MakeHistory;
use App\Jobs\Sync\LinkJob;
use App\Models\AdvertiserPublisher;
use App\Models\DeeplinkTracking;
use App\Models\DelTracking;
use App\Models\FetchDailyData;
use App\Models\History;
use App\Models\Tracking;
use App\Models\TrackingDetail;
use App\Models\User;
use App\Traits\GenerateLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class SimpleLinkService
{
    use GenerateLink;

    function storeSimple(Request $request, $advertiser, $website) {
        $url = route("track.simple", ["advertiser" => $advertiser, "website" => $website]);
        $cacheKey = "tracking_simple_tracking_" . md5($url);
        $activeAdvertiser = Cache::get($cacheKey);
        
        if (!$activeAdvertiser) {
            $activeAdvertiser = AdvertiserPublisher::where("tracking_url", $url)->first();
            if ($activeAdvertiser && $activeAdvertiser->click_through_url) {
                Cache::forever($cacheKey, $activeAdvertiser);
            }
        }
        
        if ($activeAdvertiser) {
            $activeAdvertiserLive = AdvertiserPublisher::select("status")->where("id", $activeAdvertiser->id)->first();
            $publisher = User::select("status")->where("id", $activeAdvertiser->publisher_id)->first();
            
            if ($activeAdvertiserLive->status == AdvertiserPublisher::STATUS_ACTIVE && $publisher->status == User::ACTIVE) {
                if ($activeAdvertiser->click_through_url) {
                    $this->storeTrackingData($request, $activeAdvertiser);
                    return ["success" => true, "url" => $activeAdvertiser->click_through_url];
                } else {
                    return [
                        "success" => false,
                        "view" => view("template.publisher.advertisers.link-in-progress", compact("advertiser"))
                    ];
                }
            }
        }
        
        return [
            "success" => false,
            "view" => view("template.publisher.advertisers.link-dead", compact("advertiser"))
        ];
    }

    function storeCode(Request $request, string $code) {
        $url = route("track.short", $code);
        $cacheKey = "tracking_simple_tracking_" . md5($url);
        $activeAdvertiser = Cache::get($cacheKey);

        if (!$activeAdvertiser) {
            $activeAdvertiser = AdvertiserPublisher::with("advertiser")
                ->select(["id", "publisher_id", "internal_advertiser_id", "status", "click_through_url"])
                ->where("tracking_url", $url)
                ->first();
                
            if ($activeAdvertiser && $activeAdvertiser->click_through_url) {
                Cache::forever($cacheKey, $activeAdvertiser);
            }
        }

        $advertiser = $activeAdvertiser->advertiser ?? [];
        
        if ($activeAdvertiser) {
            $activeAdvertiserLive = AdvertiserPublisher::select("status")->where("id", $activeAdvertiser->id)->first();
            $publisher = User::select("status")->where("id", $activeAdvertiser->publisher_id)->first();

            if ($activeAdvertiserLive->status == AdvertiserPublisher::STATUS_ACTIVE && $publisher->status == User::ACTIVE) {
                if ($activeAdvertiser->click_through_url) {
                    $this->storeTrackingData($request, $activeAdvertiser);
                    return ["success" => true, "url" => $activeAdvertiser->click_through_url];
                } else {
                    return [
                        "success" => false,
                        "view" => view("template.publisher.advertisers.link-in-progress", compact("advertiser"))
                    ];
                }
            }
        }
        
        return [
            "success" => false,
            "view" => view("template.publisher.advertisers.link-dead", compact("advertiser"))
        ];
    }

    function storeURLTrackingWithSubId(Request $request, Tracking $tracking) {
        $activeAdvertiser = $this->getAdvertiserApply($tracking);
        $advertiser = $activeAdvertiser->advertiser ?? [];

        if ($activeAdvertiser) {
            if ($activeAdvertiser->status == AdvertiserPublisher::STATUS_ACTIVE && $activeAdvertiser->publisher->status == User::ACTIVE) {
                if ($tracking->click_through_url) {
                    $this->makeHistory($request, $activeAdvertiser, $tracking);
                    return ["success" => true, "url" => $tracking->click_through_url];
                } else {
                    return [
                        "success" => false,
                        "view" => view("template.publisher.advertisers.link-in-progress", compact("advertiser"))
                    ];
                }
            }
        }
        
        return [
            "success" => false,
            "view" => view("template.publisher.advertisers.link-dead", compact("advertiser"))
        ];
    }

    function storeCodeTrackingWithSubId(Request $request, $code) {
        $url = route("track.short.store", $code);
        $cacheKey = "tracking_simple_tracking_with_sub_id_" . md5($url);
        $tracking = Cache::get($cacheKey);

        if (!$tracking) {
            $tracking = Tracking::select([
                "advertiser_id", "publisher_id", "website_id", "click_through_url", "hits", "id", "unique_visitor"
            ])->where("tracking_url", $url)->first();

            if (empty($tracking)) {
                $tracking = DelTracking::select([
                    "advertiser_id", "publisher_id", "website_id", "click_through_url", "hits", "id", "unique_visitor"
                ])->where("tracking_url", $url)->first();
            }

            if ($tracking && $tracking->click_through_url) {
                Cache::forever($cacheKey, $tracking);
            }
        }

        $activeAdvertiser = $this->getAdvertiserApply($tracking);

        if ($tracking) {
            if ($activeAdvertiser) {
                if ($activeAdvertiser->status == AdvertiserPublisher::STATUS_ACTIVE && $activeAdvertiser->publisher->status == User::ACTIVE) {
                    if ($tracking->click_through_url) {
                        $this->makeHistory($request, $activeAdvertiser, $tracking);
                        return ["success" => true, "url" => $tracking->click_through_url];
                    } else {
                        return [
                            "success" => false,
                            "view" => view("template.publisher.advertisers.link-in-progress", compact("advertiser"))
                        ];
                    }
                }
            }
        }

        return [
            "success" => false,
            "view" => view("template.publisher.advertisers.link-dead", compact("advertiser"))
        ];
    }

    function storeSimpleTracking(Request $request) {
        $url = route("track.simple.link", [
            "linkmid" => $request->linkmid,
            "linkaffid" => $request->linkaffid,
            "subid" => $request->subid
        ]);
        
        $cacheKey = "tracking_simple_tracking_long_" . md5($url);
        $tracking = Cache::get($cacheKey);

        if (!$tracking) {
            $tracking = Tracking::select([
                "id", "advertiser_id", "publisher_id", "website_id", "click_through_url", "hits", "unique_visitor"
            ])->where("tracking_url", $url)->first();

            if (empty($tracking)) {
                $tracking = DelTracking::select([
                    "id", "advertiser_id", "publisher_id", "website_id", "click_through_url", "hits", "unique_visitor"
                ])->where("tracking_url", $url)->first();
            }

            if ($tracking && $tracking->click_through_url) {
                Cache::forever($cacheKey, $tracking);
            }
        }

        if ($tracking) {
            $activeAdvertiser = $this->getAdvertiserApply($tracking);
        } else {
            $activeAdvertiser = AdvertiserPublisher::where("advertiser_id", $request->linkmid)
                ->where("website_id", $request->linkaffid)->first();
        }

        if ($tracking) {
            return $this->storeURLTrackingWithSubId($request, $tracking);
        } else {
            return [
                "success" => false,
                "view" => view("template.publisher.advertisers.link-dead", compact("advertiser"))
            ];
        }
    }
}
