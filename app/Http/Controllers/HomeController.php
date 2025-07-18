<?php
namespace App\Http\Controllers;

use App\Http\Requests\Publisher\WebsiteRequest;
use App\Models\Company;
use App\Models\Country;
use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
    public function redirectTo(Request $request)
    {
        $redirectTo = null;
        $user = $request->user();

        if ($user->is_admin) {
            $redirectTo = "admin.dashboard";
        } elseif ($user->is_publisher) {
            $redirectTo = "publisher.dashboard";
        } elseif ($user->is_advertiser) {
            $redirectTo = "advertiser.dashboard";
        }

        if ($redirectTo) {
            return redirect(route($redirectTo));
        }
    }
}
