<?php
namespace App\Http\Controllers\Publisher;

use App\Models\User;
use App\Models\Website;
use Illuminate\Support\Facades\Session;

class BaseController
{
    public function websiteInactiveMsg(User $user)
    {
        $website = Website::where("id", $user->active_website_id)->first();
        return 'hellowebsite';
        if (isset($website->id)) {
            if ($website->status == Website::HOLD) {
                $event = "onclick='window.open(\'https://join.skype.com/invite/rGeSJpSJ70kugq\',\'_blank\')";
                $type = "warning";
                $message = "Your website is on hold. <a href={$event} href='javascriprt: void(0)'>Contact to manager</a> for more information.";
            } elseif ($website->status == Website::REJECTED) {
                $event = "onclick='window.open(\'https://join.skype.com/invite/rGeSJpSJP8K7uq\',\'_blank\')";
                $type = "error";
                $message = "Your website is on rejected. <a href={$event} href='javascript:void(0)'>Contact to manager</a> for more information.";
            } else {
                $url = route("publisher.profile.website");
                $type = "warning";
                $message = "Please go to <a href='{$url}'>website settings</a> and verify your site to view Manager.";
            }
        } else {
            $url = route("publisher.profile.website");
            $type = "error";
            $message = "Please go to <a href='{$url}'>website settings</a> and add your site to view Manager.";
        }

        if ($type && $message) {
            Session::put($type, $message);
        }
    }

    public function returnComingSoonView($title, $headings)
    {
        return view("publisher.coming", compact("title", "headings"));
    }
}
