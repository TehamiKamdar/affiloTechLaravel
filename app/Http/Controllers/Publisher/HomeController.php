<?php
namespace App\Http\Controllers\Publisher;

use App\Models\User;
use App\Models\Company;
use App\Models\Country;
use App\Models\Website;
use App\Models\Advertiser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AdvertiserPublisher;
use App\Http\Requests\Publisher\WebsiteRequest;
use App\Services\Publisher\Dashboard\IndexService;
use App\Services\Publisher\Dashboard\CompleteProfileService;

class HomeController extends BaseController
{
    public function index(Request $request, IndexService $service)
    {
        return $service->init($request);
    }

    


    public function getAdvertiserStatus(Request $request, $publisher_id){
        $user = $request->user();
        $joined = AdvertiserPublisher::where('status', 'joined')->where('publisher_id', $publisher_id)->count();
        $rejected = AdvertiserPublisher::where('status', 'rejected')->where('publisher_id', $publisher_id)->count();
        $pending = AdvertiserPublisher::where('status', 'pending')->where('publisher_id', $publisher_id)->count();
         $joinedAdvertiserIds = AdvertiserPublisher::where('publisher_id', $publisher_id)
    ->pluck('advertiser_id');

$notJoinedCount = Advertiser::where('is_active', 1)
    ->where('is_show', 1)
    ->whereNotIn('id', $joinedAdvertiserIds)
    ->count();

        return response()->json([
            'joined' => $joined,
            'rejected' => $rejected,
            'pending'=>$pending,
            'not_joined' => $notJoinedCount,
        ]);
    }

    public function completeProfile(WebsiteRequest $request, CompleteProfileService $service)
    {
        return $service->init($request);
    }

    public function sales_chart_data(Request $request, IndexService $service){
          return $service->chart_data($request);
    }

    public function region_graph(Request $request, IndexService $service){
        return $service->performance_report($request);
    }

     public function clicks_data(Request $request, IndexService $service){
        return $service->clicks_data($request);
    }

    public function advertiserPerfomanceGraph_data(Request $request, IndexService $service){
        return $service->advertiserPerfomanceGraph($request);
    }

    public function overview_graph(Request $request, IndexService $service){
        return $service->overviewGraph($request);
    }
}
