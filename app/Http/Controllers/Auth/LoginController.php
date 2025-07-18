<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo;

 

    public function redirectTo()
    {
        $user = request()->user();

        if ($user->is_admin) {
            $this->redirectTo = '/admin/dashboard';
        } elseif ($user->is_publisher) {
            $this->redirectTo = '/publisher/dashboard';
        } elseif ($user->is_advertiser) {
            $this->redirectTo = '/advertiser/dashboard';
        }

        return $this->redirectTo;
    }

    public function authenticated($request, $user)
    {
        if ($user->is_publisher) {
            $website = $this->getActiveWebsite($user);

            if ($website) {
                $user->active_website_id = $website->id;
                $user->active_website_status = $website->status;
            }

            $user->save();
        }

        return redirect()->intended($this->redirectPath());
    }

    private function getActiveWebsite($user)
    {
        return $user->websites()->first();
    }
}
