<?php

namespace App\Http\Controllers\Doc\Advertiser;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Http\Requests\Doc\AdvertiserRequest;
use App\Http\Resources\Doc\Advertisers\AdvertiserCollection;
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
class ListController extends BaseController
{
    /**
     * Get All Advertisers
     *
     * This endpoint is used to fetch all available advertisers from the database through authentication.
     *
     * @response scenario="Get All Advertisers"{
     *"data": [
     *  {
    * "id": 57601894,
    * "name": "4 Corners Cannabis",
     * "url": "http://4cornerscannabis.com/",
    * "logo": "https://app.linkscircle.com/storage/4cornerscannabis.gif",
    * "primary_regions": [
    * "US"
    * ],
    * "supported_regions": [
    * "CA"
    * ],
    * "currency_code": null,
    * "average_payment_time": null,
    * "epc": null,
    * "click_through_url": "https://go.linkscircle.com/track/{id1}/{id2}",
    * "click_through_short_url": "https://go.linkscircle.com/short/*******",
    * "categories": [
    * "Entertainment",
    * "Lifestyle"
    * ],
    * "validation_days": "30",
    * "deeplink_enabled": 1,
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
    * "description": "<div data-v-5a495e4e=\"\" id=\"about-description\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 30px; border-bottom: 1px solid rgb(234, 235, 237); color: rgb(45, 62, 80); font-family: Muli, sans-serif;\"><div data-v-5a495e4e=\"\" class=\"about-description-section\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px;\"><h2 data-v-5a495e4e=\"\" class=\"sub-title\" style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; padding: 0px; font-size: 16px; line-height: 24px;\">Description</h2><div data-v-5a495e4e=\"\" class=\"content\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; line-height: 18px;\">4 Corners Cannabis was one of the nation’s first CBD companies. We control our entire production process, from “soil to oil,” so we know exactly what goes into our products — and what doesn’t. All of our CBD products are organic, food grade, non-GM</div></div><div data-v-5a495e4e=\"\" class=\"about-description-section\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px;\"><h2 data-v-5a495e4e=\"\" class=\"sub-title\" style=\"margin-right: 0px; margin-bottom: 10px; margin-left: 0px; padding: 0px; font-size: 16px; line-height: 24px;\">Preferred business models</h2><div data-v-5a495e4e=\"\" class=\"promo-methods-container\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;\"><div data-v-5a495e4e=\"\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px;\"><div data-v-5a495e4e=\"\" class=\"promo-method\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 3px 10px; background-color: rgb(242, 243, 244); font-size: 12px; line-height: 24px; border-radius: 5px;\">Loyalty/Rewards</div></div><div data-v-5a495e4e=\"\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px;\"><div data-v-5a495e4e=\"\" class=\"promo-method\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 3px 10px; background-color: rgb(242, 243, 244); font-size: 12px; line-height: 24px; border-radius: 5px;\">Deal/Coupons</div></div><div data-v-5a495e4e=\"\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px;\"><div data-v-5a495e4e=\"\" class=\"promo-method\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 3px 10px; background-color: rgb(242, 243, 244); font-size: 12px; line-height: 24px; border-radius: 5px;\">Social Influencer</div></div><div data-v-5a495e4e=\"\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px;\"><div data-v-5a495e4e=\"\" class=\"promo-method\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 3px 10px; background-color: rgb(242, 243, 244); font-size: 12px; line-height: 24px; border-radius: 5px;\">Content/Reviews</div></div></div></div></div><div data-v-e3ef0116=\"\" id=\"brand-stats-outer-container\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 30px; display: flex; align-items: center; justify-content: center; color: rgb(45, 62, 80); font-family: Muli, sans-serif;\"><div data-v-e3ef0116=\"\" class=\"stat\" bis_skin_checked=\"1\" style=\"margin: 0px 0px 0px auto; padding: 0px 20px 0px 0px; display: flex;\"><div data-v-e3ef0116=\"\" class=\"stat-content\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: flex; flex-direction: column; justify-content: space-between; min-height: 60px;\"><div data-v-e3ef0116=\"\" class=\"stat-label\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; color: rgb(129, 129, 129); font-size: 12px; line-height: 15px;\">30 Day EPC</div><div data-v-e3ef0116=\"\" class=\"stat-val\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-weight: 600; line-height: 24px;\">NEW</div></div></div><div data-v-e3ef0116=\"\" class=\"stat\" bis_skin_checked=\"1\" style=\"margin: 0px 0px 0px auto; padding: 0px 20px 0px 0px; display: flex;\"><span data-v-e3ef0116=\"\" class=\"separating-bar\" style=\"margin: 0px 20px 0px 0px; padding: 0px; height: 60px; border-left: 1px solid rgb(234, 235, 237);\"></span><div data-v-e3ef0116=\"\" class=\"stat-content\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: flex; flex-direction: column; justify-content: space-between; min-height: 60px;\"><div data-v-e3ef0116=\"\" class=\"stat-label\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; color: rgb(129, 129, 129); font-size: 12px; line-height: 15px;\">Response</div><div data-v-e3ef0116=\"\" class=\"stat-val\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-weight: 600; line-height: 24px;\">19%</div></div></div><div data-v-e3ef0116=\"\" class=\"stat\" bis_skin_checked=\"1\" style=\"margin: 0px 0px 0px auto; padding: 0px 20px 0px 0px; display: flex;\"><span data-v-e3ef0116=\"\" class=\"separating-bar\" style=\"margin: 0px 20px 0px 0px; padding: 0px; height: 60px; border-left: 1px solid rgb(234, 235, 237);\"></span><div data-v-e3ef0116=\"\" class=\"stat-content\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: flex; flex-direction: column; justify-content: space-between; min-height: 60px;\"><div data-v-e3ef0116=\"\" class=\"stat-label\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; color: rgb(129, 129, 129); font-size: 12px; line-height: 15px;\">Acceptance</div><div data-v-e3ef0116=\"\" class=\"stat-val\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-weight: 600; line-height: 24px;\">100%</div></div></div><div data-v-e3ef0116=\"\" class=\"stat\" bis_skin_checked=\"1\" style=\"margin: 0px 0px 0px auto; padding: 0px 20px 0px 0px; display: flex;\"><span data-v-e3ef0116=\"\" class=\"separating-bar\" style=\"margin: 0px 20px 0px 0px; padding: 0px; height: 60px; border-left: 1px solid rgb(234, 235, 237);\"></span><div data-v-e3ef0116=\"\" class=\"stat-content\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: flex; flex-direction: column; justify-content: space-between; min-height: 60px;\"><div data-v-e3ef0116=\"\" class=\"stat-label\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; color: rgb(129, 129, 129); font-size: 12px; line-height: 15px;\">Funding Status</div><div data-v-e3ef0116=\"\" class=\"stat-val\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-weight: 600; line-height: 24px;\">83%</div></div></div></div>",
    * "short_description": "4 Corners Cannabis was one of the nation’s first CBD companies. We control our entire production process, from “soil to oil,” so we know exactly what goes into our products — and what doesn’t. All of our CBD products are organic, food grade, non-GM",
    * "program_policies": "<h3 class=\"ioHeader\" style=\"margin-top: 30px; margin-right: 0px; margin-left: 0px; padding: 0px; font-size: 32px; font-weight: 500; color: rgb(45, 62, 80); font-family: Roboto, sans-serif;\">Mar 28, 2023 10:23 PDT - Onwards</h3><div class=\"tracker\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 40px; padding: 0px; border: 1px solid rgb(229, 231, 234); border-radius: 8px; color: rgb(45, 62, 80); font-family: Roboto, sans-serif; font-size: 12px;\"><div class=\"trackerHeading\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 15px 25px; font-size: 20px; width: 1121px; display: table; background-color: rgb(144, 152, 161); color: rgb(255, 255, 255); border-top-left-radius: 8px; border-top-right-radius: 8px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-cell;\"><span class=\"name\" style=\"margin: 0px; padding: 0px;\">Online Sale:</span>&nbsp;<span class=\"payout\" style=\"margin: 0px; padding: 0px;\">20%</span>&nbsp;<span class=\"currency\" style=\"margin: 0px; padding: 0px; text-transform: uppercase; color: rgb(199, 203, 208);\">USD</span></div><div class=\"trackerMinimize\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-cell; text-align: right; font-size: 22px;\"><span class=\"fa fa-minus\" style=\"margin: 0px; padding: 0px; font-weight: normal; font-stretch: normal; font-family: FontAwesome; font-size: inherit; transform: translate(0px, 0px); cursor: pointer; user-select: none;\"></span></div></div></div><div class=\"trackerContent\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 30px 25px;\"><div class=\"heading1\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 20px;\">Payout Details</div><div class=\"labelLine\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"labelCell heading2text\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\">Default Payout</div><div class=\"labelValue io_diff_line\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\">20% of order sale amount</div></div></div><div class=\"heading1 additionalTopMargin\" bis_skin_checked=\"1\" style=\"margin: 40px 0px 0px; padding: 0px; font-size: 20px;\">Schedule</div><div class=\"labelLine\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"labelCell heading2text\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\">Action Locking</div><div class=\"labelValue io_diff_line\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\">Actions are locked 1 month(s) after end of the month they are tracked</div></div></div><div class=\"labelLine\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"labelCell heading2text\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\">Invoicing</div><div class=\"labelValue io_diff_line\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\">Actions are invoiced on the 3 of the month after they lock</div></div></div><div class=\"labelLine\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"labelCell heading2text\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\">Payout Scheduling</div><div class=\"labelValue io_diff_line\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\">Approved transactions are paid 15 day(s) after the end of the day they are invoiced</div></div></div><div class=\"heading1 additionalTopMargin\" bis_skin_checked=\"1\" style=\"margin: 40px 0px 0px; padding: 0px; font-size: 20px;\">Qualified Referrals</div><div class=\"labelLine\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"labelCell heading2text\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\">Credit Policy</div><div class=\"labelValue io_diff_line\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\">Last Click</div></div></div><div class=\"labelLine\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"labelCell heading2text\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\">Referral Window</div><div class=\"labelValue io_diff_line\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\">Allow referrals from clicks within 30 day(s)</div></div></div></div></div><div class=\"tracker\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 40px; padding: 0px; border: 1px solid rgb(229, 231, 234); border-radius: 8px; color: rgb(45, 62, 80); font-family: Roboto, sans-serif; font-size: 12px;\"><div class=\"trackerHeading\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 15px 25px; font-size: 20px; width: 1121px; display: table; background-color: rgb(144, 152, 161); color: rgb(255, 255, 255); border-top-left-radius: 8px; border-top-right-radius: 8px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"generalTermsHeading\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 22px; display: table-cell;\">General Terms</div><div class=\"trackerMinimize\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-cell; text-align: right; font-size: 22px;\"><span class=\"fa fa-minus\" style=\"margin: 0px; padding: 0px; font-weight: normal; font-stretch: normal; font-family: FontAwesome; font-size: inherit; transform: translate(0px, 0px); cursor: pointer; user-select: none;\"></span></div></div></div><div class=\"trackerContent\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 30px 25px;\"><div class=\"labelLine\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table; width: 1071px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"labelCell heading2text\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\">Currency</div><div class=\"labelValue io_diff_line\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\">Financial transactions covered by this Contract will be processed in the USD currency. Currency exchanges will occur when you or your partner(s) have set a different default currency in account settings.</div></div></div><div class=\"labelLine\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"labelCell heading2text\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\">Change Notification Period</div><div class=\"labelValue io_diff_line\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\">The Contract can be changed or cancelled with 1 day(s) notification to the media partner.</div></div></div><div class=\"labelLine\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"labelCell heading2text\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\">Reversal Policy</div><div class=\"labelValue io_diff_line\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\">Reversal of performance advertising actions are decided by the Advertiser governed by a max reversal percentage of 100%</div></div></div><div class=\"labelLine\" bis_skin_checked=\"1\" style=\"margin: 20px 0px 0px; padding: 0px; display: table; width: 1071px;\"><div bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; display: table-row;\"><div class=\"labelCell heading2text\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px; font-size: 14px; display: table-cell; width: 200px;\">Media Partner Tracking Pixel</div><div class=\"labelValue io_diff_line\" bis_skin_checked=\"1\" style=\"margin: 0px; padding: 0px 0px 0px 10px; line-height: 26px; font-size: 14px; word-break: break-word; display: table-cell;\">Advertiser does NOT allow media partner to fire their tracking pixel when the consumer action is completed.</div></div></div></div></div>",
    * "type": "api",
    * "status": "joined",
    * "commission": "20%",
     * "goto_cookie_lifetime": null,
     * "exclusive": 0
     * }
     *],
     * "pagination": {
     * "total": 7024,
     * "count": 20,
     * "per_page": 20,
     * "current_page": 1,
     * "total_pages": 352
     * }
     *}
     */
    public function __invoke(AdvertiserRequest $request)
    {
        $limit = $request->limit ?? Vars::LIMIT_20;
        $user = Methods::getDocUser($request);
        $website = Website::where('wid', $request->wid)->first();

        $activeFields = $this->activeAdvertiserFields();
        $fields = $this->advertiserFields();

        $advertisers = AdvertiserPublisher::with("advertiser:{$fields}")
                                    ->select($activeFields)
                                    ->where('publisher_id', $user->publisher_id)
                                    ->where('website_id', $website->id ?? null)
                                    ->paginate($limit);

        ApiHistory::create([
            "publisher_id" => $user->publisher_id,
            "website_id" => $website->id,
            "wid" => $request->wid,
            "name" => "List Advertisers",
            "token" => $request->header('token'),
            "page" => $request->page,
            "limit" => $limit,
            "ip" => request()->ip(),
        ]);

        return new AdvertiserCollection($advertisers);
    }
}
