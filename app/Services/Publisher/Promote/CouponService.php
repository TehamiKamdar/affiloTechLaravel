<?php
namespace App\Services\Publisher\Promote;

use App\Models\User;
use App\Models\Website;
use Illuminate\Http\Request;
use App\Services\Publisher\Promote\BaseService;

class CouponService extends BaseService
{
    public function init(Request $request)
    {
        $user = $request->user();

        if (auth()->user()->status != User::STATUS_ACTIVE && auth()->user()->is_completed) {
            $this->userInactiveMsg($user);
        } elseif (empty($user->active_website_id) || $user->active_website_status != Website::ACTIVE) {
            $this->websiteInactiveMsg($user);
        }

        $data = $this->couponLogic($request);

        if ($request->ajax()) {
            return $data;
        }

        $coupons = $data->get('coupons');
        $total = $data->get('total');
        $to = $data->get('to');
        $from = $data->get('from');

        $title = "Coupons";

        seo()->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Promote',
            $title
        ];

        return view("publisher.promote.coupons", compact(
            'title',
            'headings',
            'coupons',
            'total',
            'to',
            'from'
        ));
    }
}
