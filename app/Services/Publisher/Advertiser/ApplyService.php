<?php
namespace App\Services\Publisher\Advertiser;

use App\Helper\Vars;
use App\Models\AdvertiserPublisher;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class ApplyService extends BaseService
{
    public function init(Request $request)
    {
        $user = $request->user();
        if(!empty($user->active_website_id) && $user->active_website_status != 'pending'){
             if(!empty($request->advertisers)){
          $data = [];
          
            foreach ($request->advertisers as $value) {
              
            $advertiserData = explode("-", $value);
            $advertiserSource = $advertiserData[0];
            $advertiserID = $advertiserData[1];
            $advertiser = Advertiser::where('sid',$advertiserID)->first();
       $criteria = [
    'advertiser_id'   => $advertiser->id,
    'source'          => $advertiserSource,
    'publisher_id'    => $user->publisher_id,
    'website_id'      => $user->active_website_id,
    "advertiser_sid" => $advertiser->sid,
];

// Try to find existing record
$publisher = AdvertiserPublisher::where('advertiser_id',$criteria)->where('source',$advertiserSource)->where('publisher_id',$user->publisher_id)->where('website_id',$user->active_website_id)->first();

$data = [
    'applied_at' => now(),
    'status'     => 'pending',
];

if ($publisher) {
    // Update existing
    $publisher->update($data);
} else {
    // Create new
    $publisher = AdvertiserPublisher::create(array_merge($criteria, $data));
}
$data [] = $publisher;

            }
            return response()->json(["data"=>$data,"status"=>true]);
      }else{
          $advertiser = Advertiser::find($request->advertiser_id);

$advertiserID = $advertiser->id;
$advertiserSource = $advertiser->source;

AdvertiserPublisher::updateOrCreate(
    [
        "advertiser_id" => $advertiserID,
        "source" => $advertiserSource,
        "publisher_id" => $user->publisher_id,
        "website_id" => $user->active_website_id,
        "status" => 'pending', // all these act like "where"
    ],
    [
        "applied_at" => now(),
        "advertiser_sid" => $advertiser->sid,
    ]
);
      }
       
        $cacheKey = $this->generateAdvertiserCacheKey($request); // however you generated it
    Cache::forget($cacheKey);
        $previousUrl = url()->previous();

// Check if 'is_update' already exists in the query string
if (!str_contains($previousUrl, 'is_update=')) {
    // Add either ? or & based on whether there's already a query string
    $separator = str_contains($previousUrl, '?') ? '&' : '?';
    $previousUrl .= $separator . 'is_update=update';
}

return redirect()->to($previousUrl)->with("success", "Successfully applied for affiliate actions. Please wait for approval.");
        }else{
            if(empty($user->active_website_id)){
                return redirect()->back()->with("error", "No Website Added.");
            }else{
                return redirect()->back()->with("error", "Atleast one website must be active");
            }
            
        }
     
    }
    
    protected function generateAdvertiserCacheKey(Request $request)
{
    $userId = auth()->id();
    $page = $request->get('page', 1);
    $filters = $request->except('page');

   return 'advertisers_' . md5(json_encode($filters)) . "_user_{$userId}_page_{$page}";
}

}
