<?php
namespace App\Services\Publisher\Setting;

use App\Helper\Methods;
use App\Http\Requests\Publisher\WebsiteUpdateRequest;
use App\Http\Requests\Publisher\UpdateWebsiteRequest;
use App\Http\Resources\Publisher\Website\ListingCollection;
use App\Http\Resources\Publisher\Website\ListingResource;
use App\Models\Country;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteInformationService
{
    const WEB_VERIFY_KEY = "theaffiloverifycode";

    public function init(Request $request)
    {
        $title = "Websites";

        seo()->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Profile',
            $title
        ];

        $user = $request->user();
        $publisher = $user->publisher;
        $websites = Website::where('user_id', $user->id)->get();
        $websites = new ListingCollection($websites);
        $websites = $websites->toArray($request);

        $websites = collect($websites)->map(function ($item) {
            return (object) $item;
        })->toArray();

        $categories = Methods::getCategories();
        $methods = Methods::getWebsiteType();
        $countries = Country::where('status', 'active')->get();

        return view("publisher.settings.website", compact('title', 'headings', 'websites', 'categories', 'methods', 'countries', 'publisher'));
    }

    public function getWebsiteById(Website $website)
    {
        return new ListingResource($website);
    }

    public function updateWebsite(UpdateWebsiteRequest $request)
    {
        try {
            $user = auth()->user();
            $user->load('websites');

            $website = $user->websites->where('id', $request->website_id)->first();

            if ($website) {
                $status = $website->status;
                if ($website->url != $request->website_url) {
                    $status = Website::PENDING;
                }

                $website->update([
                    "url" => $request->website_url,
                    "name" => $request->website_name,
                    "categories" => $request->categories,
                    "partner_types" => $request->website_type,
                    "monthly_traffic" => $request->monthly_traffic,
                    "monthly_page_views" => $request->monthly_page_views,
                    "country" => $request->website_country,
                    "intro" => $request->website_intro,
                    "status" => $status
                ]);
            } else {
                $website = Website::create([
                    "user_id" => $user->id,
                    "wid" => Methods::generateWebsiteBarcodeNumber(),
                    "url" => $request->website_url,
                    "name" => $request->website_name,
                    "categories" => $request->categories,
                    "partner_types" => $request->website_type,
                    "monthly_traffic" => $request->monthly_traffic,
                    "monthly_page_views" => $request->monthly_page_views,
                    "country" => $request->website_country,
                    "intro" => $request->website_intro,
                    "status" => Website::PENDING
                ]);
            }

            if (!isset(auth()->user()->active_website_id)) {
                $user->update([
                    'active_website_id' => $website->id
                ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Website successfully updated.",
                "data" => new ListingResource($website)
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                "success" => false,
                "message" => $exception->getMessage(),
                "data" => []
            ], 400);
        }
    }

    public function verifyWebsite(Request $request)
    {
        try {
            $url = $request->url;
            $metas = get_meta_tags("{$url}");

            $uid = $metas[self::WEB_VERIFY_KEY];
            $web = Website::where("id", $uid)->first();

            if ($web) {
                $web->update([
                    'status' => Website::ACTIVE
                ]);

                User::where('id', auth()->user()->id)->update([
                    "active_website_id" => $uid
                ]);

                return response()->json([
                    "success" => true,
                    "message" => "Website verification successfully updated."
                ], 200);
            }

            return response()->json([
                "success" => false,
                "message" => "Website verification could not be found on the provided URL. Please ensure that the meta tag has been correctly added to your website."
            ], 200);
        } catch (\Exception $exception) {
            return response()->json([
                "success" => false,
                "message" => $exception->getMessage()
            ], 400);
        }
    }

    public function setWebsite(Request $request, Website $website)
    {
        $user = $request->user();
        $user->update([
            'active_website_id' => $website->id
        ]);

        return redirect()->route("publisher.dashboard")->with("success", "Website successfully changed.");
    }
}
