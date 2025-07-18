<?php

namespace App\Services\Publisher\Track;

use App\Helper\DeepLinkGenerate;
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
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class DeepLinkService
{
    use DeepLink;

    /**
     * @param Request $request
     * @param string $code
     * @return array|null
     */
    public function storeLongCode(Request $request): ?array
    {
        $ued = urldecode($request->ued);
        $url = route("track.deeplink.long", ["linkmid" => $request->linkmid, "linkaffid" => $request->linkaffid, "subid" => $request->subid, "ued" => $ued]);

        $cacheKey = 'deeplink_long_code_tracking_' . md5($url);
        $deeplinkTracking = Cache::get($cacheKey);

        if (!$deeplinkTracking) {
            $deeplinkTracking = DeeplinkTracking::with('advertiser')
                ->select('id', 'publisher_id', 'website_id', 'advertiser_id', 'click_through_url', 'tracking_url', 'tracking_url_long', 'sub_id', 'hits', 'unique_visitor', 'click_through_url', 'landing_url')
                ->where("tracking_url_long", $url)
                ->first();

            if($deeplinkTracking && empty($deeplinkTracking->click_through_url))
            {
                $website = Website::select('id')->where("wid", $request->linkaffid)->first();
                $activeAdvertiser = AdvertiserPublisher::where('advertiser_sid', $request->linkmid)->where('website_id',$website->id)
                                                    ->first();

                $link = new DeepLinkGenerate();
                $clickLink = $link->generate($activeAdvertiser->advertiser, $deeplinkTracking->publisher_id, $deeplinkTracking->website_id, $deeplinkTracking->sub_id, $deeplinkTracking->landing_url);


                if(!empty($clickLink))
                    $deeplinkTracking->update([
                        'click_through_url' => $clickLink
                    ]);

            }

            if(empty($deeplinkTracking))
            {
                $deeplinkTracking = DelDeeplinkTracking::with('advertiser')
                    ->select('id', 'publisher_id', 'website_id', 'advertiser_id', 'click_through_url', 'tracking_url', 'tracking_url_long', 'sub_id', 'hits', 'unique_visitor', 'click_through_url')
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
            ->where("website_id", $request->linkaffid)
            ->where("status", AdvertiserPublisher::STATUS_ACTIVE)
            ->first();

        if (!$advertiser) {
            return [
                "success" => false,
                "view" => view("publisher.advertisers.deeplink-dead", compact('advertiser'))
            ];
        }

        $link = new DeeplinkGenerate();
        $clickLink = $link->generate($advertiser->advertiser, $advertiser->publisher_id, $advertiser->website_id, $request->subid, $ued);

        if(!isset($deeplinkTracking->id))
        {
            $deeplinkTracking = DeeplinkTracking::create([
                "advertiser_id" => $advertiser->advertiser_id,
                "website_id" => $advertiser->website_id,
                "publisher_id" => $advertiser->publisher_id,
                "tracking_url_long" => $url,
                "sub_id" => $request->subid,
                "landing_url" => $ued,
                "click_through_url" => $clickLink
            ]);
        }

        $deeplinkTracking->new_advertiser_id = $advertiser->id;

        $this->makeHistory($request, $deeplinkTracking);

        return [
            "success" => true,
            "url" => $deeplinkTracking->click_through_url ?? null
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

        $deeplinkTracking = DeeplinkTracking::with('advertiser')
            ->select([
                'id',
                'publisher_id',
                'website_id',
                'advertiser_id',
                'click_through_url',
                'tracking_url',
                'tracking_url_long',
                'sub_id',
                'hits',
                'unique_visitor',
                'click_through_url'
            ])->where("tracking_url", $url)->first();



        if(empty($deeplinkTracking))
        {
            $deeplinkTracking = DelDeeplinkTracking::with('advertiser')
                ->select([
                    'id',
                    'publisher_id',
                    'website_id',
                    'advertiser_id',
                    'click_through_url',
                    'tracking_url',
                    'tracking_url_long',
                    'sub_id',
                    'hits',
                    'unique_visitor',
                    'click_through_url'
                ])->where("tracking_url", $url)->first();
        }

        if($deeplinkTracking && (empty($deeplinkTracking->click_through_url) || $deeplinkTracking->click_through_url == '')) {


            $advertiserApply = AdvertiserPublisher::with('advertiser')->where('advertiser_id', $deeplinkTracking->advertiser_id)
                ->where('website_id', $deeplinkTracking->website_id)
                ->first();
            $advertiser = $advertiserApply->advertiser ?? null;

            if(empty($advertiser))
            {
                $advertiserApply = AdvertiserPublisher::with('advertiser')->where('advertiser_id', $deeplinkTracking->advertiser_id)
                    ->where('website_id', $deeplinkTracking->website_id)
                    ->first();
                $advertiser = $advertiserApply->advertiser ?? null;

                if(empty($advertiser))
                {
                    $advertiser = Advertiser::where('id', $deeplinkTracking->advertiser_id)->first();

                    if(empty($advertiser))
                    {
                        $advertiser = Advertiser::where('api_advertiser_id', $deeplinkTracking->advertiser_id)->first();
                    }
                }
            }

            if($advertiser) {
                $link = new DeeplinkGenerate();
                $clickLink = $link->generate($advertiser, $deeplinkTracking->publisher_id, $deeplinkTracking->website_id, $deeplinkTracking->sub_id, $deeplinkTracking->landing_url);

                if (empty($clickLink))
                {
                    $clickLink = null;
                }
                $deeplinkTracking->update([
                    'click_through_url' => $clickLink
                ]);
            }
        }

        if($deeplinkTracking && $deeplinkTracking->click_through_url)
        {
            return $this->getClickThroughURL($request, $deeplinkTracking);
        }
        elseif ($deeplinkTracking && $deeplinkTracking->click_through_url == '')
        {
            $advertiserApply = AdvertiserPublisher::with('advertiser')->where('advertiser_id', $deeplinkTracking->advertiser_id)
                ->where('website_id', $deeplinkTracking->website_id)
                ->first();
            $advertiser = $advertiserApply->advertiser ?? null;

            if(empty($advertiser))
            {
                $advertiserApply = AdvertiserPublisher::with('advertiser')->where('advertiser_id', $deeplinkTracking->advertiser_id)
                    ->where('website_id', $deeplinkTracking->website_id)
                    ->first();
                $advertiser = $advertiserApply->advertiser ?? null;

                if(empty($advertiser))
                {
                    $advertiser = Advertiser::where('id', $deeplinkTracking->advertiser_id)->first();

                    if(empty($advertiser))
                    {
                        $advertiser = Advertiser::where('api_advertiser_id', $deeplinkTracking->advertiser_id)->first();
                    }
                }
            }

            return [
                "success" => false,
                "view" => view("publisher.advertisers.link-in-process", compact('advertiser'))
            ];
        }
        else{
            return [
                "success" => false,
                "view" => view("publisher.advertisers.deeplink-dead")
            ];
        }
    }

    private function  getClickThroughURL(Request $request, $deeplinkTracking)
    {
        
        if(isset($deeplinkTracking->id)) {

            $advertiserApplyCheck = AdvertiserPublisher::select(['id','status','publisher_id','advertiser_id'])
                ->with(['publisher:id,status', 'advertiser'])
                ->where("publisher_id", $deeplinkTracking->publisher_id)
                ->where("website_id", $deeplinkTracking->website_id)
                ->where("advertiser_id", $deeplinkTracking->advertiser_id)
                ->first();

            $advertiser = $deeplinkTracking->advertiser;

            if(isset($advertiserApplyCheck->status) && $advertiserApplyCheck->status == AdvertiserPublisher::STATUS_ACTIVE)
            {
                if($deeplinkTracking->click_through_url)
                {
                    $deeplinkTracking->new_advertiser_id = $advertiserApplyCheck->id;
                   
                    $this->storeTrackingData($request, $deeplinkTracking);
                    
                    return [
                        "success" => true,
                        "url" => $deeplinkTracking->click_through_url
                    ];
                } else
                {
                    return [
                        "success" => false,
                        "view" => view("publisher.advertisers.link-in-process", compact('advertiser'))
                    ];
                }
            }
            else
            {
                Methods::customWarning("Deep Link Service", $deeplinkTracking);
            }

            $advertiser = $deeplinkTracking ? $deeplinkTracking->advertiser : null;

            return [
                "success" => false,
                "view" => view("publisher.advertisers.deeplink-dead", compact('advertiser'))
            ];

        }
        return null;
    }

    private function storeTrackingData(Request $request, $deeplinkTracking, $clickLink = null)
    {
         
        $cacheKey = 'deeplink_store_tracking_data_' . $deeplinkTracking->tracking_url_long . '_' . $clickLink ?? $deeplinkTracking->click_through_url . '_' . $deeplinkTracking->tracking_url;
        $track = Cache::get($cacheKey);

        if (!$track) {

            $track = DeeplinkTracking::select([
                'id',
                'publisher_id',
                'website_id',
                'advertiser_id',
                'hits',
                'unique_visitor',
                'click_through_url'
            ])->where([
                    'advertiser_id' => $deeplinkTracking->advertiser_id,
                    'website_id' => $deeplinkTracking->website_id,
                    'publisher_id' => $deeplinkTracking->publisher_id,
                    'tracking_url' => $deeplinkTracking->tracking_url,
                    'tracking_url_long' => $deeplinkTracking->tracking_url_long,
                    'click_through_url' => $clickLink ?? $deeplinkTracking->click_through_url,
                    'sub_id' => $deeplinkTracking->sub_id,
                ])->first();

            if(empty($track))
            {

                $track = DelDeeplinkTracking::select([
                    'id',
                    'publisher_id',
                    'website_id',
                    'advertiser_id',
                    'hits',
                    'unique_visitor',
                    'click_through_url'
                ])->where([
                    'advertiser_id' => $deeplinkTracking->advertiser_id,
                    'website_id' => $deeplinkTracking->website_id,
                    'publisher_id' => $deeplinkTracking->publisher_id,
                    'tracking_url' => $deeplinkTracking->tracking_url,
                    'tracking_url_long' => $deeplinkTracking->tracking_url_long,
                    'click_through_url' => $clickLink ?? $deeplinkTracking->click_through_url,
                    'sub_id' => $deeplinkTracking->sub_id,
                ])->first();
            }

            if ($track && $track->click_through_url) {
                Cache::forever($cacheKey, $track);
            }
        }

        if(!isset($deeplinkTracking->id))
        {
            $track = DeeplinkTracking::create([
                'advertiser_id' => $deeplinkTracking->advertiser_id,
                'website_id' => $deeplinkTracking->website_id,
                'publisher_id' => $deeplinkTracking->publisher_id,
                'tracking_url' => $deeplinkTracking->tracking_url,
                'tracking_url_long' => $deeplinkTracking->tracking_url_long,
                'click_through_url' => $clickLink ?? $deeplinkTracking->click_through_url,
                'sub_id' => $deeplinkTracking->sub_id,
            ]);
        }

        $track->new_advertiser_id = $deeplinkTracking->new_advertiser_id;
       
        $this->makeHistory($request, $track);
    }

    private function makeHistory(Request $request, $deeplinkTracking)
    {

        $jobClassTracking = "App\Models\DeeplinkTracking";
        $jobClassTrackingDetail = "App\Models\DeeplinkTrackingDetail";

        History::updateOrCreate([
            "path" => "MakeHistoryDeeplinkTrackingJob",
            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
            "advertiser_id" => $deeplinkTracking->new_advertiser_id,
            'website_id' => $deeplinkTracking->website_id,
            'publisher_id' => $deeplinkTracking->publisher_id,
            'sub_id' => $deeplinkTracking->sub_id,
            "date" => Vars::CUSTOM_DATE_FORMAT_2,
            "queue" => Vars::MAKE_HISTORY,
            "source" => Vars::GLOBAL
        ], [
            "name" => "Make the History.",
            "payload" => json_encode([
                "ip" => $request->ip(),
                "tracking_id" => $deeplinkTracking->id,
                "active_advertiser_apply_id" => $deeplinkTracking->new_advertiser_id,
                "model_tracking" => $jobClassTracking,
                "model_tracking_detail" => $jobClassTrackingDetail
            ]),
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
        ]);

    }

    protected function moveTrackingLinks($chunkSize)
    {
        // Columns for each table
        $trackingColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id',
            'click_through_url', 'tracking_url_long', 'tracking_url_short',
            'tracking_url', 'sub_id', 'hits', 'unique_visitor', 'created_at', 'updated_at'
        ];

        $sourceTable = "trackings";
        $destTable = "del_trackings";
        $this->moveData($sourceTable, $chunkSize, $destTable, $trackingColumns);
    }

    protected function moveTrackingLinkDetails($chunkSize)
    {
        // Columns for each table
        $trackingDetailColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id', 'tracking_id',
            'ip_address', 'operating_system', 'browser', 'device',
            'referer_url', 'country', 'iso2', 'region', 'city',
            'zipcode', 'created_at', 'updated_at'
        ];

        $sourceTable = "tracking_details";
        $destTable = "del_tracking_details";
        $this->moveData($sourceTable, $chunkSize, $destTable, $trackingDetailColumns);
    }

    protected function moveDeepTrackingLinks($chunkSize)
    {
        // Columns for each table
        $deeplinkTrackingColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id',
            'click_through_url', 'tracking_url_long', 'tracking_url',
            'sub_id', 'hits', 'unique_visitor', 'created_at', 'updated_at'
        ];

        $sourceTable = "deeplink_trackings";
        $destTable = "del_deeplink_trackings";
        $this->moveData($sourceTable, $chunkSize, $destTable, $deeplinkTrackingColumns);
    }

    protected function moveDeepTrackingLinkDetails($chunkSize)
    {
        // Columns for each table
        $trackingDetailColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id', 'tracking_id',
            'ip_address', 'operating_system', 'browser', 'device',
            'referer_url', 'country', 'iso2', 'region', 'city',
            'zipcode', 'created_at', 'updated_at'
        ];

        $sourceTable = "deeplink_tracking_details";
        $destTable = "del_deeplink_tracking_details";
        $this->moveData($sourceTable, $chunkSize, $destTable, $trackingDetailColumns);
    }

    protected function moveCouponTrackingLinks($chunkSize)
    {
        // Columns for each table
        $couponTrackingColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id',
            'click_through_url', 'tracking_url', 'hits', 'unique_visitor', 'created_at', 'updated_at'
        ];

        $sourceTable = "coupon_trackings";
        $destTable = "del_coupon_trackings";
        $this->moveData($sourceTable, $chunkSize, $destTable, $couponTrackingColumns);
    }

    protected function moveCouponTrackingLinkDetails($chunkSize)
    {
        // Columns for each table
        $trackingDetailColumns = [
            'id', 'advertiser_id', 'publisher_id', 'website_id', 'tracking_id',
            'ip_address', 'operating_system', 'browser', 'device',
            'referer_url', 'country', 'iso2', 'region', 'city',
            'zipcode', 'created_at', 'updated_at'
        ];

        $sourceTable = "coupon_tracking_details";
        $destTable = "del_coupon_tracking_details";
        $this->moveData($sourceTable, $chunkSize, $destTable, $trackingDetailColumns);
    }

    protected function moveData($sourceTable, $chunkSize, $destTable, $columns)
    {
        $records = DB::table($sourceTable)
            ->where('is_deleted', 1)
            ->take($chunkSize)
            ->get();

        $destRecords = [];
        foreach($records as $record)
        {
            $destRecord = [];
            foreach ($columns as $column) {
                $destRecord[$column] = $record->$column;
            }
            $destRecords[] = $destRecord;
        }

        DB::table($destTable)->insert($destRecords);

        // Delete the moved records
        $recordIds = array_column($records->toArray(), 'id');
        DB::table($sourceTable)->whereIn('id', $recordIds)->delete();

    }
}
