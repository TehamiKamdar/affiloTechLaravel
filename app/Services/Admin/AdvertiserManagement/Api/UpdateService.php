<?php
namespace App\Service\Admin\AdvertiserManagement\Api;

use App\Models\Advertiser;
use App\Models\Commission;
use App\Models\ValidationDomain;
use Illuminate\Http\Request;

class UpdateService
{
    public function init(Request $request, Advertiser $api_advertiser)
    {
        try {
            $advertiserData = [];

            if (isset($request->name)) {
                $advertiserData["name"] = $request->name;
            }
             if (isset($request->commission)) {
                $advertiserData["commission"] = $request->commission;
            }
             if (isset($request->commission_type)) {
                $advertiserData["commission_type"] = $request->commission_type;
            }
            if (isset($request->url)) {
                $advertiserData["url"] = $request->url;
            }
            if (isset($request->primary_region)) {
                $advertiserData["primary_regions"] = [$request->primary_region];
            }
            if (isset($request->currency_code)) {
                $advertiserData["currency_code"] = $request->currency_code;
            }
            if (isset($request->average_payment_time)) {
                $advertiserData["average_payment_time"] = $request->average_payment_time;
            }
            if (isset($request->epc)) {
                $advertiserData["epc"] = $request->epc;
            }
            if (isset($request->click_through_url)) {
                $advertiserData["click_through_url"] = $request->click_through_url;
            }
            if (isset($request->deeplink_enabled)) {
                $advertiserData["deeplink_enabled"] = $request->deeplink_enabled;
            }
            if (isset($request->categories)) {
                $advertiserData["categories"] = $request->categories;
            }
            if (isset($request->tags)) {
                $advertiserData["tags"] = $request->tags;
            }
            if (isset($request->offer_type)) {
                $advertiserData["offer_type"] = $request->offer_type;
            }
            if (isset($request->supported_regions)) {
                $advertiserData["supported_regions"] = $request->supported_regions;
            }
            if (isset($request->program_restrictions)) {
                $advertiserData["program_restrictions"] = $request->program_restrictions;
            }
            if (isset($request->promotional_methods)) {
                $advertiserData["promotional_methods"] = $request->promotional_methods;
            }
            if (isset($request->description)) {
                $advertiserData["description"] = $request->description;
            }
            if (isset($request->short_description)) {
                $advertiserData["short_description"] = $request->short_description;
            }
            if (isset($request->program_policies)) {
                $advertiserData["program_policies"] = $request->program_policies;
            }
           if (isset($request->logo)) {
                if(!empty($request->logo)){
                    if($request->hasFile('logo')){
                          $filename = uniqid() . '.' . $request->file('logo')->getClientOriginalExtension();
    $storagePath = $request->file('logo')->storeAs('logos', $filename, 'public');

    // Store the path in the database
    $advertiserData["logo"] = 'storage/logos/' . $filename;
                    }else{
                        $advertiserData["logo"] = $request->logo;
                    }
                }else{
                     $advertiserData["logo"] = null;
                }
            }
            if (isset($request->custom_domain)) {
                $advertiserData["custom_domain"] = $request->custom_domain ?? null;
            }

            $api_advertiser->update($advertiserData);

            if ($request->removeCommission) {
                Commission::whereIn("id", $request->removeCommission)->delete();
            }

            if ($request->removeValidation) {
                ValidationDomain::whereIn("id", $request->removeValidation)->delete();
            }

            foreach ($request->commissions ?? [] as $commission) {
                if (in_array($commission["commission_id"], $request->removeCommission ?? [])) {
                    continue;
                }
                
                Commission::updateOrCreate(
                    [
                        "id" => $commission["commission_id"] ?? null,
                        "advertiser_id" => $api_advertiser->id
                    ],
                    [
                        "created_by" => auth()->user()->name,
                        "date" => $commission["date"] ?? null,
                        "condition" => $commission["condition"] ?? null,
                        "rate" => $commission["rate"] ?? null,
                        "type" => $commission["type"] ?? null,
                        "info" => $commission["info"] ?? null
                    ]
                );
            }

            if (isset($request->validations)) {
                foreach (array_column($request->validations, "domain") as $validation) {
                    if ($validation) {
                        ValidationDomain::updateOrCreate(
                            [
                                "name" => $validation,
                                "advertiser_id" => $api_advertiser->id
                            ],
                            ["created_by" => auth()->user()->name]
                        );
                    }
                }
            }

            $response = [
                "type" => "success",
                "message" => "Advertiser API Data Successfully Updated."
            ];
        } catch (\Exception $exception) {
            $response = [
                "type" => "error",
                "message" => $exception->getMessage()
            ];
        }

        return redirect()
            ->route("admin.advertiser-management.api-advertisers.index")
            ->with($response["type"], $response["message"]);
    }
}
