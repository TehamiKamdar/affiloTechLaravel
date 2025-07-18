<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\Country;
use App\Models\Mix;
use App\Services\Admin\AdvertiserService;
use Illuminate\Http\Request;

class AdvertiserController extends Controller
{
    public $advertiserService;

    public function __construct(AdvertiserService $advertiserService)
    {
        $this->advertiserService = $advertiserService;
    }

    public function getApiAdvertiser(Request $request)
    {
        $data = $this->advertiserService->index($request);
        $title = $data->get("title");

        // return $data;
        return view("admin.advertiser.api", compact("title"));
    }

    public function ajax(Request $request)
    {
      
        return $this->advertiserService->ajax($request);
    }

    public function view(Request $request, Advertiser $advertiser)
    {
        $data = $this->advertiserService->view($request, $advertiser);

        $advertiser = $data->get("advertiser");
        $methods = $data->get("methods");
        $restrictions = $data->get("restrictions");
        $categories = $data->get("categories");
        $primaryRegions = $data->get("primaryRegions");
        $supportedRegions = $data->get("supportedRegions");
        $countryFullName = $data->get("countryFullName");
        $id = $advertiser["id"] ?? null;

        return view("admin.advertiser.view", compact(
            "advertiser",
            "id",
            "methods",
            "restrictions",
            "categories",
            "primaryRegions",
            "countryFullName",
            "supportedRegions"
        ));
    }

    public function viewCommissionRates(Request $request, Advertiser $advertiser)
    {
        $data = $this->advertiserService->viewCommissionRates($request, $advertiser);

        $advertiser = $data->get("advertiser");
        $commissions = $data->get("commissions");
        $id = $advertiser["id"] ?? null;

        return view("admin.advertiser.view", compact(
            "advertiser",
            "id",
            "commissions"
        ));
    }

    public function viewTerms(Request $request, Advertiser $advertiser)
    {
        $data = $this->advertiserService->viewTerms($request, $advertiser);

        $advertiser = $data->get("advertiser");
        $id = $advertiser["id"] ?? null;

        return view("admin.advertiser.view", compact(
            "advertiser",
            "id"
        ));
    }
    
    public function edit($id){
        $advertiser = Advertiser::find($id);
        $countries = Country::all();
        $categories = Mix::where('type','category')->get();
         $methods = Mix::where('type','promotional_method')->get();
         return view("admin.advertiser.edit", compact(
            "advertiser","countries","categories","methods"
        ));
    }
    
     public function update(Request $request, $id){
        $advertiser = Advertiser::find($id);
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
    $advertiserData["logo"] = 'logos/' . $filename;
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

            $advertiser->update($advertiserData);

            if ($request->removeCommission) {
                Commission::whereIn("id", $request->removeCommission)->delete();
            }

            if ($request->removeValidation) {
                ValidationDomain::whereIn("id", $request->removeValidation)->delete();
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
            ->route("admin.advertisers.api")
            ->with(["message" => "Advertiser API Data Successfully Updated."]);
    }
}
