<?php
namespace App\Services\Publisher\Track;

use App\Helper\CouponLinkGenerate;
use App\Helper\Static\Vars;
use App\Jobs\Sync\LinkJob;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\Coupon;
use App\Models\CouponTracking;
use App\Models\CouponTrackingDetail;
use App\Models\FetchDailyData;
use App\Models\Website;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Stevebauman\Location\Facades\Location;

class CouponLinkService
{
    /**
     * Store a simple coupon tracking record.
     */
    public function storeSimple(Request $request, Advertiser $advertiser, Website $website, Coupon $coupon)
    {
        $advertiserApplyCheck = AdvertiserPublisher::where('website_id', $website->id)
            ->where('advertiser_id', $advertiser->id)
            ->first();

        if (isset($advertiserApplyCheck->status) && $advertiserApplyCheck->status == AdvertiserPublisher::STATUS_ACTIVE) {
            $track = $this->getCouponTrackRecord($request, $advertiser, $website, $coupon, $advertiserApplyCheck);
            $trackURL = $track->click_through_url ?? $advertiserApplyCheck->click_through_url;

            if ($trackURL) {
                $agent = new Agent();
                $device = null;

                if ($agent->isDesktop()) {
                    $device = 'desktop';
                } elseif ($agent->isTablet()) {
                    $device = 'tablet';
                } elseif ($agent->isPhone()) {
                    $device = 'phone';
                }

                $ip = $request->ip();
                $ip = ($ip == '::1' || $ip == '127.0.0.1') ? '110.93.196.117' : $ip;
                $location = Location::get($ip);

                $track->increment('hits');

                $checkUniqueVisitor = CouponTrackingDetail::where('ip_address', $ip)
                    ->where('tracking_id', $track->id)
                    ->count();

                if ($checkUniqueVisitor == 0) {
                    $track->increment('unique_visitor');
                }

                $referer = request()->headers->get('referer');

                CouponTrackingDetail::create([
                    'advertiser_id' => $track->advertiser_id,
                    'website_id' => $track->website_id,
                    'publisher_id' => $track->publisher_id,
                    'tracking_id' => $track->id,
                    'ip_address' => $ip,
                    'operating_system' => $agent->platform(),
                    'browser' => $agent->browser(),
                    'device' => $device,
                    'referer_url' => $referer,
                    'country' => isset($location->countryName) && is_string($location->countryName) ? $location->countryName : null,
                    'iso2' => isset($location->countryCode) && is_string($location->countryCode) ? $location->countryCode : null,
                    'region' => isset($location->regionName) && is_string($location->regionName) ? $location->regionName : null,
                    'city' => isset($location->cityName) && is_string($location->cityName) ? $location->cityName : null,
                    'zipcode' => $location->zipCode ?? null,
                ]);

                return [
                    'success' => true,
                    'url' => $trackURL
                ];
            } else {
                return [
                    'success' => false,
                    'view' => view('template.publisher.advertisers.link-in-process', compact('advertiser'))
                ];
            }
        }

        return [
            'success' => false,
            'view' => view('template.publisher.advertisers.link-dead', compact('advertiser'))
        ];
    }

    /**
     * Retrieve or create a coupon tracking record.
     */
    public function getCouponTrackRecord(Request $request, Advertiser $advertiser, Website $website, Coupon $coupon, AdvertiserApply $advertiserApplyCheck)
    {
        $track = CouponTracking::where('tracking_url', route('track.coupon', [
            'advertiser' => $advertiser->id,
            'website' => $website->id,
            'coupon' => $coupon->id
        ]))->first();

        if ($track) {
            return $track;
        }

        return $this->storeCouponTrack($request, $advertiser, $website, $coupon, $advertiserApplyCheck);
    }

    /**
     * Store a new coupon tracking record.
     */
    public function storeCouponTrack(Request $request, Advertiser $advertiser, Website $website, Coupon $coupon, AdvertiserApply $advertiserApplyCheck)
    {
        $user = $website->users->first();
        $trackURLGet = new CouponLinkGenerate();

        if ($coupon->source == Vars::ADMITAD) {
            $trackURL = $trackURLGet->generate($advertiser->source, $user->id, $website->id, $coupon);
        } elseif ($coupon->url_tracking && !($coupon->source == Vars::TRADEDOUBLER)) {
            $trackURL = $trackURLGet->generate($advertiser->source, $user->id, $website->id, $coupon->url_tracking);
        } else {
            $trackURL = $advertiserApplyCheck->click_through_url;
        }

        return CouponTracking::updateOrCreate(
            [
                'advertiser_id' => $advertiser->id,
                'website_id' => $website->id,
                'publisher_id' => $user->id,
                'coupon_id' => $coupon->id
            ],
            [
                'click_through_url' => $trackURL,
                'tracking_url' => route('track.coupon', [
                    'advertiser' => $advertiser->id,
                    'website' => $website->id,
                    'coupon' => $coupon->id
                ])
            ]
        );
    }
}
