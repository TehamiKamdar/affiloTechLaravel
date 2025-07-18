<?php
namespace App\Services\Publisher\Setting;

use App\Models\City;
use App\Models\Country;
use App\Models\State;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyInformationService
{
    public function init(Request $request)
    {
        $title = "Company Information";

        seo()->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Profile',
            $title
        ];

        $user = $request->user();
        $company = $user->company;
        $publisher = $user->publisher;
        $countries = Country::orderBy("name", "ASC")->get()->toArray();
        $states = Country::where('id', $company->country ?? 0)->first();
        $cities = City::where("state_id", $company->state ?? 0)->get()->toArray();

        $states = $states ? $states->states : [];
        // $cities = $cities ? $cities->cities : [];

        return view("publisher.settings.company-information", compact(
            'title', 'headings', 'user', 'company', 'countries', 'states', 'cities', 'publisher'
        ));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $company = $user->company;
if($company){
     $company->update([
            "company_name" => $request->company_name,
            "contact_name" => $request->legal_entity_type,
            "legal_entity_type" => $request->legal_entity_type,
            "city" => $request->city,
            "state" => $request->state,
            "country" => $request->country,
            "address" => $request->address,
            "address_2" => $request->address_2,
        ]);

}else{
    $company= Company::create([
        "user_id" => $user->id,
        "company_name" => $request->company_name,
         "phone_number" => $request->phone_number,
            "contact_name" => $request->legal_entity_type,
            "legal_entity_type" => $request->legal_entity_type,
            "city" => $request->city,
            "state" => $request->state,
            "country" => $request->country,
            "address" => $request->address,
            "address_2" => $request->address_2,
        ]);
}

        $redirectURL = $request->server('HTTP_REFERER');

        return redirect($redirectURL)->with('success', 'Company Information Successfully Updated.');
    }
}
