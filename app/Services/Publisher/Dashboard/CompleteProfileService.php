<?php
namespace App\Services\Publisher\Dashboard;

use App\Helper\Methods;
use App\Http\Requests\Publisher\WebsiteRequest;
use App\Models\Company;
use App\Models\Publisher;
use App\Models\User;
use App\Models\Website;

class CompleteProfileService
{
    public function init(WebsiteRequest $request)
    {
        $user = $request->user();

        // Update user to mark profile as completed
        User::where("id", $user->id)->update([
            "is_completed" => 1
        ]);

        // Parse the website URL
        $url = $request->website_url;
        $parsedUrl = parse_url($url);
        $domain = $parsedUrl["host"] ?? null;

        // Create or update website information
        $website = Website::updateOrCreate(
            ["user_id" => $user->id],
            [
                "wid" => Methods::generateWebsiteBarcodeNumber(),
                "name" => $domain,
                "categories" => [$request->website_category],
                "partnership_types" => [$request->website_type],
                "url" => $request->website_url,
                "status" => Website::PENDING,
                "country" => $request->country,
                "intro" => $request->website_intro
            ]
        );

        // Create or update company information
        Company::updateOrCreate(
            ["user_id" => $user->id],
            [
                "company_name" => $request->company_name,
                "contact_name" => $request->first_name . " " . $request->last_name,
                "phone_number" => $request->phone_number,
                "address" => $request->address,
                "country" => $request->country
            ]
        );

        // Create publisher record
        Publisher::create(["user_id" => $user->publisher_id]);

        // Update user status
        $user->update([
            "active_website_status" => Website::PENDING,
            "active_website_id" => $website->id
        ]);

        // Redirect to the provided URL with success message
        $redirectURL = $request->server("HTTP_REFERER");
        return redirect($redirectURL)->with(
            "success",
            "Company Profile Successfully Updated."
        );
    }
}
