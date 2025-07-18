<?php
namespace App\Services\Publisher\Setting;

use App\Models\City;
use App\Models\Country;
use App\Models\Mediakit;
use App\Models\Publisher;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class BasicInformationService
{
    public function init(Request $request)
    {
        $title = "Basic Information";

        seo()->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Profile',
            $title
        ];

        $countries = Country::orderBy("name", "ASC")->get()->toArray();

        $user = auth()->user();
        $user->load('publisher');
        $publisher = $user->publisher;

        $states = $cities = [];
        if (isset($publisher->location_country) && $publisher->location_country) {
            $states = State::select('id', 'name')->where("country_id", $publisher->location_country)->get()->toArray();
        }
        if (isset($publisher->location_state) && $publisher->location_state) {
            $cities = City::select('id', 'name')->where("state_id", $publisher->location_state)->get()->toArray();
        }

        $mediakits = Mediakit::where('user_id', $user->id)->get();

        $languages = Publisher::getLanguages();

        $years = collect(range(now()->subYears(49)->format("Y"), now()->format("Y")))->reverse();

        $months = [
            "january",
            "february",
            "march",
            "april",
            "may",
            "june",
            "july",
            "august",
            "september",
            "october",
            "november",
            "december",
        ];

        return view("publisher.settings.basic-information", compact(
            'title',
            'headings',
            'user',
            'publisher',
            'countries',
            'states',
            'cities',
            'months',
            'years',
            'languages',
            'mediakits'
        ));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        User::updateOrCreate(
            ['id' => $user->id],
            ['name' => $request->name]
        );
        $language = [$request->language];
        $country = [$request->country];

        $publisherData = [
    "user_name" => $request->user_name,
    "language" => json_encode($language), // Convert array to JSON if necessary
    "customer_reach" => json_encode($country), // Convert array to JSON if necessary
            "gender" => $request->gender,
            "dob" => $request->dob,
            "location_country" => $request->location_country,
            "location_state" => $request->location_state,
            "location_city" => $request->location_city,
            "location_address_1" => $request->location_address_1,
            "intro" => $request->bio
        ];

        if ($request->file('avatar')) {
            $filePath = $request->file('avatar')->store('uploads', 'public');
            $publisherData['image'] = $filePath;
        }

        $publisher = Publisher::updateOrCreate(
            ['user_id' => $user->publisher_id],
            $publisherData
        );

        if ($request->mediakit_image) {
            $size = $request->mediakit_image->getSize();
            $filename = uniqid($publisher->sid . '_') . "." . $request->mediakit_image->getClientOriginalName();
            $request->mediakit_image->move(public_path('media_kits'), $filename);

            $kit = 'media_kits/' . $filename;

            Mediakit::create([
                'user_id' => $user->id,
                'name' => $filename,
                'size' => number_format($size / 1048576, 2),
                'image' => $kit
            ]);
        }

        $redirectURL = $request->server('HTTP_REFERER');
        
        return redirect($redirectURL)->with('success', 'Basic Information Successfully Updated.');
    }

    public function deleteMediaKit(Mediakit $mediakit)
    {
        unlink($mediakit->image);
        $mediakit->delete();

        return redirect()->route('publisher.profile.basic-information')->with("success", "Media Kit Successfully Deleted.");
    }
}
