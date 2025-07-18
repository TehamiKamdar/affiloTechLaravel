<?php

namespace App\Http\Controllers\Doc\Offer;

use App\Helper\Static\Methods;
use App\Http\Requests\Doc\OfferShowRequest;
use App\Http\Resources\Doc\Offers\OfferResource;
use App\Models\ApiHistory;
use App\Models\Coupon;
use App\Models\Website;

/**
 * @group Offers
 *
 * APIs for managing Advertisers
 *
 * @authenticated
 */
class ShowController extends BaseController
{
    /**
     * Get Offer By ID
     *
     * This endpoint is used to fetch get offer by id available in the database.
     *
     * @urlParam id string required The ID of the offer. Example: 0004f9f0-4224-4228-8e85-5a58683c0862
     *
     * @response scenario="Get Advertiser By ID"{
     * "data": {
     * "id": "0004f9f0-4224-4228-8e85-5a58683c0862",
     * "name": "The Kobo Elipsa Pack has all you need to mark up your eBooks and documents, and create your own notebooks. Includes a Kobo Stylus and SleepCover!",
     * "advertiser_id": 62947499,
     * "advertiser_name": "Rakuten Kobo Australia",
     * "advertiser_url": "https://www.kobo.com/au/en",
     * "url_tracking": "http://track.test.local/track/f5ca51a4-e843-4005-a96f-db88ddfc9916/d5b45a0d-5604-4872-b3d1-8dbf315b586c/0004f9f0-4224-4228-8e85-5a58683c0862",
     * "advertiser_status": "active",
     * "type": "promotion",
     * "description": null,
     * "terms": null,
     * "start_date": "14/04/2022",
     * "end_date": "14/04/2028",
     * "code": "No code required",
     * "exclusive": 0,
     * "regions": null,
     * "categories": null
     * }
     * }
     */
    public function __invoke(Coupon $coupon, OfferShowRequest $request)
    {
        $user = Methods::getDocUser($request);
        $website = Website::where('wid', $request->wid)->first();
        $coupon = $coupon->select($this->couponFields())->whereHas('advertiser_applies_without_auth', function ($query) use($user, $website) {
            $query->select($this->activeAdvertiserFields())->where('advertiser_publishers.publisher_id', $user->publisher_id)->where('website_id', $website->id ?? null);
        })->with(['advertiser_applies_without_auth' => function ($query) use($user, $website) {
            $query->select($this->activeAdvertiserFields())->where('advertiser_publishers.publisher_id', $user->publisher_id)->where('website_id', $website->id ?? null);
        }]);
        if($request->advertiser_id)
            $coupon = $coupon->where('sid', $request->advertiser_id);
        $coupon = $coupon->first();

        ApiHistory::create([
            "publisher_id" => $user->publisher_id,
            "website_id" => $website->id,
            "wid" => $request->wid,
            "by_id" => $coupon->id,
            "name" => "Show Coupon",
            "token" => $request->header('token'),
            "ip" => request()->ip(),
        ]);

        return new OfferResource($coupon);
    }
}
