<?php

namespace App\Http\Controllers\Doc\Website;

use App\Helper\Static\Methods;
use App\Http\Resources\Doc\Websites\WebsiteResource;
use App\Models\ApiHistory;
use App\Models\Website;
use Illuminate\Http\Request;

/**
 * @group Websites
 *
 *  APIs for managing Websites
 * *
 * @authenticated
 */
class ShowController extends BaseController
{
    /**
     * Get Website By ID
     *
     * @urlParam id integer required The ID of the website. Example: 90644632
     *
     * This endpoint is used to fetch all available websites from the database through authentication.
     *
     * @response scenario="Get Website By ID"
     * {
     * "data": [
     * {
     * "id": 90644632,
     * "name": "****************",
     * "url": "https://www.*********.au/",
     * "partner_types": [
     * "Coupons/Deals",
     * "Content/Blog"
     * ],
     * "categories": [
     * "News & Blogging",
     * "Business",
     * "Shopping",
     * "Lifestyle"
     * ],
     * "status": "Active",
     * "monthly_traffic": "25000",
     * "monthly_page_views": "55000",
     * "last_updated": "08/14/2023"
     * }
     * ],
     * "pagination": {
     * "total": 1,
     * "count": 1,
     * "per_page": 20,
     * "current_page": 1,
     * "total_pages": 1
     * }
     * }
     *
     */
     public function __invoke($id, Request $request)
     {
        $user = Methods::getDocUser($request);
        $website = Website::select($this->websiteFields())->where('wid', $id)->whereHas('users', function($query) use($user) {
            return $query->where("id", $user->id);
        })->orderBy("name","ASC")->first();

         ApiHistory::create([
             "publisher_id" => $user->publisher_id,
             "website_id" => $website->id,
             "wid" => $id,
             "by_id" => $id,
             "name" => "Show Website",
             "token" => $request->header('token'),
             "ip" => request()->ip(),
         ]);

        return new WebsiteResource($website);
    }
}
