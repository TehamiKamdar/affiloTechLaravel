<?php
namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\State;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function actionStates(Request $request)
    {
        $states = Country::where("id", $request->country_id)
            ->first()
            ->states;

        $states = $states->map(function ($state) {
            return collect([
                "id" => $state->id,
                "name" => ucwords($state->name),
            ]);
        });

        return response()->json($states);
    }

    public function actionCities(Request $request)
    {
        $cities = State::where("id", $request->state_id)
            ->where("country_id", $request->country_id)
            ->first()
            ->cities;

        $cities = $cities->map(function ($city) {
            return collect([
                "id" => $city->id,
                "name" => ucwords($city->name),
            ]);
        });

        return response()->json($cities);
    }
}
