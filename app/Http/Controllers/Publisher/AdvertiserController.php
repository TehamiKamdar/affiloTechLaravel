<?php
namespace App\Http\Controllers\Publisher;

use App\Models\Advertiser;
use App\Services\Publisher\Advertiser\ApplyService;
use App\Services\Publisher\Advertiser\ExportService;
use App\Services\Publisher\Advertiser\FindService;
use App\Services\Publisher\Advertiser\MyService;
use App\Services\Publisher\Advertiser\NewService;
use App\Services\Publisher\Advertiser\TopService;
use App\Services\Publisher\Advertiser\ViewService;
use Illuminate\Http\Request;

class AdvertiserController extends BaseController
{
    public function getMyAdvertiser(Request $request, MyService $service)
    {
        return $service->init($request);
    }

    public function getTopAdvertiser(Request $request, TopService $service)
    {
        return $service->init($request);
    }

    public function getNewAdvertiser(Request $request, NewService $service)
    {
        return $service->init($request);
    }

    public function getFindAdvertiser(Request $request, FindService $service)
    {
        return $service->init($request);
    }

    public function viewAdvertiser(Request $request, Advertiser $advertiser, ViewService $service)
    {
        return $service->init($request, $advertiser);
        // return "You are on right page";
    }

    public function applyAdvertiser(Request $request, ApplyService $service)
    {

        return $service->init($request);
    }

    public function generateExportAdvertiser(Request $request, ExportService $service)
    {
        return $service->init($request);
    }
}
