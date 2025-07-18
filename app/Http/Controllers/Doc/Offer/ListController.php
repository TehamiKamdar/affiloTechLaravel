<?php

namespace App\Http\Controllers\Doc\Offer;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Http\Requests\Doc\OfferRequest;
use App\Http\Resources\Doc\Offers\OfferCollection;
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
class ListController extends BaseController
{
    /**
     * Get All Offers
     *
     * This endpoint is used to fetch all available offers from the database through authentication.
     *
     * @response scenario="Get All Offers"{
     *  {
     * "data": [
     * {
     * "id": "cf8bee9a-14ee-4eca-99bb-1979893f5d81",
     * "name": "Co-Found Private Link",
     * "advertiser_id": 18393049,
     * "advertiser_name": "99designs by Vista",
     * "advertiser_url": "https://99designs.com",
     * "url_tracking": "http://track.test.local/track/d5592ba4-3247-48fd-bc13-9b124acb889f/d5b45a0d-5604-4872-b3d1-8dbf315b586c/cf8bee9a-14ee-4eca-99bb-1979893f5d81",
     * "advertiser_status": "active",
     * "type": "promotion",
     * "description": "",
     * "terms": null,
     * "start_date": "11/08/2023",
     * "end_date": "11/08/2023",
     * "code": "No code required",
     * "exclusive": 0,
     * "regions": null,
     * "categories": null
     * },
     * {............},
     * ],
     * "pagination": {
     * "total": 832,
     * "count": 50,
     * "per_page": 50,
     * "current_page": 1,
     * "total_pages": 17
     * }
     * }
     */
    public function __invoke(OfferRequest $request)
    {
        $limit = $request->limit ?? Vars::LIMIT_20;
        $user = Methods::getDocUser($request);
        $website = Website::where('wid', $request->wid)->first();
        $coupons = Coupon::select($this->couponFields())->whereHas('advertiser_applies_without_auth', function ($query) use($user, $website) {
            $query->select($this->activeAdvertiserFields())->where('advertiser_publishers.publisher_id', $user->publisher_id)->where('website_id', $website->id ?? null);
        })->with(['advertiser_applies_without_auth' => function ($query) use($user, $website) {
            $query->select($this->activeAdvertiserFields())->where('advertiser_publishers.publisher_id', $user->publisher_id)->where('website_id', $website->id ?? null);
        }]);
        if($request->advertiser_id)
            $coupons = $coupons->where('sid', $request->advertiser_id);
        $coupons = $coupons->orderBy("advertiser_name","ASC")->paginate($limit);

        ApiHistory::create([
            "publisher_id" => $user->publisher_id,
            "website_id" => $website->id,
            "wid" => $request->wid,
            "name" => "List Coupons",
            "token" => $request->header('token'),
            "page" => $request->page,
            "limit" => $limit,
            "ip" => request()->ip(),
        ]);
        return new OfferCollection($coupons);
    }
}
