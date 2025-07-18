<?php
namespace App\Services\Publisher\Reporting\Transaction;

use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;

class ClickPerformance extends BaseService
{
    public function init(Request $request)
    {
        $user = $request->user();

        if (auth()->user()->status != User::STATUS_ACTIVE && auth()->user()->is_completed) {
            $this->userInactiveMsg($user);
        } elseif (empty($user->active_website_id) || $user->active_website_status != Website::ACTIVE) {
            $this->websiteInactiveMsg($user);
        }

        $data = $this->ClickLogic($request);

        if ($request->ajax()) {
            return $data;
        }

        $clicks = $data->get('clicks');
        $total = $data->get('total');
        $total_clicks = $data->get('total_clicks');
        $to = $data->get('to');
        $from = $data->get('from');

        $title = "Click Performance";

        seo()->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Reporting',
            $title
        ];

        return view("publisher.reporting.click_performance", compact(
            'title',
            'headings',
            'clicks',
            'total',
            'total_clicks',
            'to',
            'from'
        ));
    }
}
