<?php

namespace App\Http\Controllers\Doc\Advertiser;

use App\Helper\Static\Methods;
use App\Http\Requests\Doc\AdvertiserRequest;
use App\Http\Requests\Doc\AdvertiserShowRequest;
use App\Http\Resources\Doc\Advertisers\AdvertiserResource;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\ApiHistory;
use App\Models\Website;

/**
 * @group Advertisers
 *
 * APIs for managing Advertisers
 *
 * @authenticated
 */
class ShowController extends BaseController
{
    /**
     * Get Advertiser By ID
     *
     * This endpoint is used to fetch get advertiser by id available in the database.
     *
     * @urlParam id integer required The ID of the advertiser. Example: 86049368
     *
     * @response scenario="Get Advertiser By ID"{
     * "data": {
     * "id": 86049368,
     * "name": " ESM-Computer",
     * "url": "https://www.esm-computer.de/",
     * "logo": "https://app.linkscircle.com/",
     * "primary_regions": [
     * "DE"
     * ],
     * "supported_regions": [],
     * "currency_code": "EUR",
     * "average_payment_time": "14",
     * "epc": "0",
     * "click_through_url": null,
     * "click_through_short_url": null,
     * "validation_days": null,
     * "status": "Not Joined",
     * "commission": "7%",
     * "goto_cookie_lifetime": null,
     * "exclusive": 0,
     * "deeplink_enabled": 1,
     * "categories": null,
     * "program_restrictions": [
     * "PPC Site",
     * "TM+ Bidding"
     * ],
     * "promotional_methods": [
     * "Social Media",
     * "Email Marketing",
     * "Blog Site",
     * "Content Site",
     * "Coupon Site"
     * ],
     * "description": null,
     * "short_description": null,
     * "program_policies": null
     * }
     */
    public function __invoke($id, AdvertiserShowRequest $request)
    {

        $activeFields = $this->activeAdvertiserFields();
        $fields = $this->advertiserFields();
        $user = Methods::getDocUser($request);
        $website = Website::where('wid', $request->wid)->first();
        $advertiser = AdvertiserPublisher::with("advertiser:{$fields}")
                                    ->select($activeFields)
                                    ->where('advertiser_sid', $id)
                                    ->where('publisher_id', $user->publisher_id)
                                    ->where('website_id', $website->id ?? null)
                                    ->first();

        ApiHistory::create([
            "publisher_id" => $user->publisher_id,
            "website_id" => $website->id,
            "wid" => $request->wid,
            "by_id" => $id,
            "name" => "Show Advertiser",
            "token" => $request->header('token'),
            "ip" => request()->ip(),
        ]);

        return new AdvertiserResource($advertiser);
    }
}
