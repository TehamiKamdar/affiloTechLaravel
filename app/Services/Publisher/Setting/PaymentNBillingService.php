<?php
namespace App\Services\Publisher\Setting;

use App\Models\City;
use App\Models\Country;
use App\Models\PaymentSetting;
use App\Models\Billing;
use App\Models\State;
use Illuminate\Http\Request;

class PaymentNBillingService
{
    public function init(Request $request)
    {
        $title = "Payment N Billing";

        seo()->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Profile',
            $title
        ];

        $user = $request->user();
                $publisher = $user->publisher;

        $payment = PaymentSetting::where('user_id', $user->id)->first();
        $billing = Billing::where('user_id', $user->id)->first();
        $countries = Country::orderBy("name", "ASC")->get();
        $states = State::all();
        $cities = City::all();

        return view("publisher.settings.payment_n_billing", compact('title', 'headings', 'payment','billing','countries','states','cities', 'publisher'));
    }
}
