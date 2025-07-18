<?php

namespace App\Http\Controllers\Doc\Generate;

use App\Helper\Static\Methods;
use App\Http\Controllers\Controller;
use App\Http\Requests\Doc\TrackingLinkRequest;
use App\Http\Requests\Publisher\DeeplinkRequest;
use App\Models\ApiHistory;
use App\Models\Website;
use App\Services\Publisher\Advertiser\DeepLinkService;
use App\Services\Publisher\Advertiser\DocTrackingLinkService;

/**
 * @group Generate Deep / Tracking Links
 *
 * APIs for managing Generate Deep / Tracking Links
 *
 * @authenticated
 */
class TrackingLinkController extends Controller
{
    /**
     * Generate Tracking Links
     *
     * This endpoint is used to generate tracking links by id available in the database.
     *
     * @urlParam id integer required The ID of the advertiser. Example: 86049368
     *
     * @response scenario="Generate Tracking Links"
     *
     * {
     * "success": true,
     * "name": "99designs by Vista",
     * "tracking_url": "https://go.linkscircle.com/short/NaxDGFkv",
     * }
     *
     */
    public function __invoke($id, TrackingLinkRequest $request, DocTrackingLinkService $service)
    {
        $user = Methods::getDocUser($request);

        $website = Website::where('wid', $request->wid)->first();

        if(isset($website->id) && $website->id)
        {
            $request->merge(['widgetAdvertiser' => $id]);

            ApiHistory::create([
                "publisher_id" => $user->publisher_id ?? null,
                "website_id" => $website->id ?? null,
                "wid" => $request->wid,
                "by_id" => $id,
                "name" => "Generate Tracking Link",
                "token" => $request->header('token'),
                "ip" => request()->ip(),
            ]);

            return $service->checkAvailability($request, $user, $website->id);
        }

        return response()->json([
            "success" => false,
            "name" => $advertiser->name ?? null,
            "tracking_url" => $track ?? null
        ], 404);
    }
}
