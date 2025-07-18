<?php

namespace App\Http\Controllers\Publisher\Track;

use App\Http\Controllers\Controller;
use App\Models\Advertiser;
use App\Models\Coupon;
use App\Models\Website;
use App\Services\Publisher\Track\CouponLinkService;
use Illuminate\Http\Request;

class TrackCouponLinkController extends Controller
{
    protected object $service;
    public function __construct(CouponLinkService $service)
    {
        $this->service = $service;
    }

    public function actionURLTracking(Request $request, Advertiser $advertiser, Website $website, Coupon $coupon)
    {

        $data = $this->service->storeSimple($request, $advertiser, $website, $coupon);

        if(!empty($data))
        {
            if($data['success'])
                return redirect()->away($data['url']);

            else
                return $data['view'];
        }

        abort(404);

    }
}
