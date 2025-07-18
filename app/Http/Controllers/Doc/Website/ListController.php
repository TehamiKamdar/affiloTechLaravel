<?php

namespace App\Http\Controllers\Doc\Website;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Http\Requests\Doc\WebsiteRequest;
use App\Http\Resources\Doc\Websites\WebsiteCollection;
use App\Models\ApiHistory;
use App\Models\Website;
use Illuminate\Http\Request;

/**
 * @group Websites
 *
 * APIs for managing Websites
 *
 * @authenticated
 */
class ListController extends BaseController
{
    /**
     * Get All Websites
     *
     * This endpoint is used to fetch all available websites from the database through authentication.
     *
     * @response scenario="Get All Websites"
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
     */
    public function __invoke(WebsiteRequest $request)
    {
        $limit = $request->limit ?? Vars::LIMIT_20;
        $user = Methods::getDocUser($request);
        $websites = Website::select($this->websiteFields())->whereHas('users', function($query) use($user) {
            return $query->where("id", $user->id);
        })->orderBy("name","ASC")->paginate($limit);

        ApiHistory::create([
            "publisher_id" => $user->publisher_id,
            "name" => "List Websites",
            "token" => $request->header('token'),
            "page" => $request->page,
            "limit" => $limit,
            "ip" => request()->ip(),
        ]);

        return new WebsiteCollection($websites);
    }
}
