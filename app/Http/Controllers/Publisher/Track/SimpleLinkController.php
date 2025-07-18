<?php

namespace App\Http\Controllers\Publisher\Track;

use App\Http\Controllers\Controller;
use App\Models\Tracking;
use App\Services\Publisher\Track\SimpleLinkService;
use Illuminate\Http\Request;

class SimpleLinkController extends Controller
{
    protected object $service;
    public function __construct(SimpleLinkService $service)
    {
        $this->service = $service;
        
    }

    public function actionURLTracking(Request $request, $advertiser, $website)
    {
       
        $data = $this->service->storeSimple($request, $advertiser, $website);

        if(!empty($data))
        {
            if($data['success'])
                return redirect()->away($data['url']);

            else
                return $data['view'];
        }

        abort(404);
    }
    public function actionShortURLTracking(Request $request, string $code)
    {
       
        $data = $this->service->storeCode($request, $code);

        if(!empty($data))
        {
          
            if($data['success'])
                return redirect()->away($data['url']);

            else
                return $data['view'];
        }

        abort(404);
    }
    public function actionURLTrackingWithSubId(Request $request, Tracking $tracking)
    {
         
        $data = $this->service->storeURLTrackingWithSubId($request, $tracking);

        if(!empty($data))
        {
            if($data['success'])
                return redirect()->away($data['url']);

            else
                return $data['view'];
        }

        abort(404);
    }
    public function actionCodeTrackingWithSubId(Request $request, string $code)
    {
        
        $data = $this->service->storeCodeTrackingWithSubId($request, $code);

        if(!empty($data))
        {
            if($data['success'])
                return redirect()->away($data['url']);

            else
                return $data['view'];
        }

        abort(404);
    }
    public function actionCodeTrackingLong(Request $request)
    {
       
        $data = $this->service->storeSimpleTracking($request);

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
