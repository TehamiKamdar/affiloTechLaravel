<?php

namespace App\Http\Controllers\Publisher;

use App\Classes\RandomStringGenerator;
use App\Enums\ExportType;
use App\Exports\DeepLinkExport;
use App\Helper\DeeplinkGenerate;
use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Http\Controllers\Controller;
use App\Http\Requests\Publisher\DeeplinkRequest;
use App\Http\Requests\Publisher\TrackinglinkRequest;
use App\Jobs\DeeplinkGenerateJob;
use App\Jobs\Sync\LinkJob;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\DeeplinkTracking;
use App\Models\FetchDailyData;
use App\Models\GenerateLink as GenerateLinkModel;
use App\Models\Tracking;
use App\Models\Website;
use App\Traits\DeepLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class DeeplinkController extends Controller
{
    use DeepLink;

    public function actionDeeplink(Request $request)
    {
        $limit = Vars::DEFAULT_PUBLISHER_DEEP_LINKS_PAGINATION;
        if (session()->has('publisher_depp_link_limit')) {
            $limit = session()->get('publisher_depp_link_limit');
        }

        $websites = Website::withAndWhereHas('users', function ($user) {
            return $user->where('id', auth()->user()->id);
        })->where('status', Website::ACTIVE)->count();

        $links = [];
        $total = 0;
        $from = 0;
$to = 0;
        $perPage = $request->get("per_page", 50);
        $page = $request->get("page", 1);

        if ($websites) {
            $links = DeeplinkTracking::select([
                'advertisers.name',
                'advertisers.sid',
                'advertisers.url',
                'deeplink_trackings.landing_url',
                'deeplink_trackings.tracking_url_long',
                'deeplink_trackings.tracking_url',
                'deeplink_trackings.sub_id',
            ])
            ->join('advertisers', 'advertisers.id', '=', 'deeplink_trackings.advertiser_id')
            ->where('deeplink_trackings.publisher_id', auth()->user()->publisher_id)
            ->where('deeplink_trackings.website_id', auth()->user()->active_website_id)
            ->where(function ($query) use ($request) {
                $query->orWhereNotNull('deeplink_trackings.tracking_url');
                $query->orWhereNotNull('deeplink_trackings.tracking_url_long');
            });

            if ($request->search_by_name) {
                $links = $links->where(function ($query) use ($request) {
                    $query->orWhere('advertisers.name', 'LIKE', "%{$request->search_by_name}%")
                          ->orWhere('deeplink_trackings.tracking_url', 'LIKE', "%{$request->search_by_name}%")
                          ->orWhere('advertisers.url', 'LIKE', "%{$request->search_by_name}%")
                          ->orWhere('advertisers.sid', 'LIKE', "%{$request->search_by_name}%")
                          ->orWhere('deeplink_trackings.sub_id', 'LIKE', "%{$request->search_by_name}%");
                });
            }

            $links = $links->orderBy('deeplink_trackings.created_at', 'DESC')
                ->paginate($perPage, ["*"], "page", $page);
            $total = $links->total();
                 $from = ($links->currentPage() - 1) * $links->perPage() + 1;
        $to = min($links->currentPage() * $links->perPage(), $links->total());
            $title = "Deep Links";

        seo()->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Promote',
            $title
        ];
        

        } else {
            $url = route('publisher.profile.website');
            $message = "Please go to <a href='{$url}'>website settings</a> and verify your site to view Creative Coupons.";
            Session::put('error', $message);
            $links = new LengthAwarePaginator([], 0, $perPage, $page);
        }
         $title = "Deep Links";
         seo()->title(default: "{$title} — " . env("APP_NAME"));

        $headings = [
            'Promote',
            $title
        ];

        if ($request->ajax()) {
            $returnView = view('publisher.promote.deep-link', compact("links", "total", "to", "from", "title", "headings"))->render();
            return response()->json([
                 "title" => $title,
                "headings" => $headings,
                "total" => $total,
                "html" => $returnView,
                'to' => $to,
                'from' => $from
            ]);
        }

        return view('publisher.promote.deep-link', compact("links", "total", "to", "from", "title", "headings"));
    }

    public function actionExport(ExportType $type, Request $request)
    {
        try {
            if (ExportType::CSV == $type) {
                return Excel::download(new DeepLinkExport($request), 'deep-link.csv');
            } elseif (ExportType::XLSX == $type) {
                return Excel::download(new DeepLinkExport($request), 'deep-link.xlsx');
            }
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', $exception->getMessage());
        }
    }

    public function actionDeeplinkGenerate(Request $request)
    {
        return view('template.publisher.tools.deeplink.view');
    }

    public function actionCheckAvailability(DeeplinkRequest $request)
    {
        $advertiser = Advertiser::select(
            'id', 'sid', 'name', 'deeplink_enabled', 'advertiser_id', 'source', 'click_through_url', 'url', 'custom_domain'
        )->where('sid', $request->widgetAdvertiser)->first();

        $apply = AdvertiserPublisher::select(
            'id', 'website_id', 'tracking_url_short'
        )
        ->where('advertiser_sid', $request->widgetAdvertiser)
        ->where('publisher_id', auth()->user()->publisher_id)
        ->where('website_id', auth()->user()->active_website_id)
        ->first();

        $deepLinkEnable = $advertiser->deeplink_enabled ?? false;
           

        if ($advertiser && isset($apply->id) && $deepLinkEnable && $request->landing_url) {
            
            return $this->createNGetDeeplink($request, $advertiser, $apply);
        } else {
            if ($deepLinkEnable == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'This advertiser is not allowed to create deep links.'
                ]);
            }
            return response()->json([
                'success' => false,
                'message' => 'Please enter a valid advertiser URL.'
            ]);
        }
    }

   public function actionTrackingURLCheckAvailability(TrackinglinkRequest $request)
{
    $advertiser = Advertiser::select('id', 'sid', 'name','click_through_url')
        ->where('sid', $request->widgetAdvertiser)
        ->first();

    $apply = AdvertiserPublisher::select('id', 'website_id','tracking_url','publisher_id')
        ->where('advertiser_sid', $request->widgetAdvertiser)
        ->where('publisher_id', auth()->user()->publisher_id)
        ->where('website_id', auth()->user()->active_website_id)
        ->first();

    if (!$advertiser || !$apply) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid advertiser or application details.'
        ]);
    }

if(!empty($request->sub_id)){
     $subID = $request->sub_id;
}else{
     $subID = null;
}
    $subID = $request->sub_id;
    $landingUrl = $request->landing_url;

    // Fetch existing tracking link
    $tracking = Tracking::select('tracking_url_short', 'tracking_url_long')
        ->where([
            'advertiser_id' => $advertiser->id,
            'publisher_id' => $apply->publisher_id,
            'website_id' => $apply->website_id,
            'sub_id' => $subID,
        ])
        ->first();
        
      

    if ($tracking) {
        return response()->json([
            'success' => true,
            'name'=>$advertiser->name,
            'tracking_url_short' => $tracking->tracking_url_short,
            'tracking_url_long' => $tracking->tracking_url_long,
        ]);
    }

    // Generate new tracking link
    $website = Website::select('wid')
        ->where('id', $apply->website_id)
        ->first();

    if (!$website) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid website details.'
        ]);
    }

    $shortLink = $this->generateShortLink();
    $longLink = $this->generateLongLink($advertiser->sid, $website->wid, $subID, auth()->user()->publisher_id);

    // Save the tracking link
    $newTracking = Tracking::create([
        'advertiser_id' => $advertiser->id,
        'publisher_id' => auth()->user()->publisher_id,
        'website_id' => $apply->website_id,
        'sub_id' => $subID,
        'tracking_url'=>$apply->tracking_url,
        'click_through_url' => $advertiser->click_through_url,
        'tracking_url_short' => $shortLink,
        'tracking_url_long' => $longLink,
    ]);

    return response()->json([
        'success' => true,
        'name'=>$advertiser->name,
        'tracking_url_short' => $newTracking->tracking_url_short,
        'tracking_url_long' => $newTracking->tracking_url_long,
    ]);
}

  private function createNGetDeeplink(DeeplinkRequest $request, Advertiser $advertiser, AdvertiserPublisher $apply)
    {
        // Assuming this is within a controller method

        $url = $request->landing_url;

// Trim and sanitize the URL
        $url = trim($url);
        $url = filter_var($url, FILTER_SANITIZE_URL);

// Ensure URL has a scheme
        if (!preg_match('/^http[s]?:\/\//', $url)) {
            $url = 'http://' . $url;
        }


// Validate the URL
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return response()->json([
                "success" => false,
                "message" => "Please enter a valid advertiser URL."
            ]);
        }

// Parse the URL and get the host
        $parsedUrl = parse_url($url);
        $domain = $parsedUrl['host'] ?? null;

        if (!$domain) {
            return response()->json([
                "success" => false,
                "message" => "Please enter a valid advertiser URL."
            ]);
        }

// Remove 'www.' prefix if present
        $domain = preg_replace('/^www\./', '', $domain);

        $shortLink = $longLink = null;
if (empty($advertiser->custom_domain)) {
    $host = parse_url($advertiser->url, PHP_URL_HOST);
    $advertiser_domain = preg_replace('/^www\./', '', $host);
} else {
    $advertiser_domain = $advertiser->custom_domain;
}

        if ($domain == $advertiser_domain) {
            $user = auth()->user();
            $subID = $request->sub_id ?? null;
            $landing_url = $request->landing_url ?? null;

            // Fetch existing deeplink if it exists
            $deeplink = DeeplinkTracking::select([
                'advertiser_id', 'publisher_id', 'website_id', 'landing_url',
                'sub_id', 'click_through_url', 'tracking_url', 'tracking_url_long'
            ])
                ->where([
                    "advertiser_id" => $advertiser->id,
                    "website_id" => $apply->website_id,
                    "publisher_id" => $user->publisher_id,
                    "landing_url" => $landing_url,
                    "sub_id" => $subID
                ])->first();
                
               

            if (!$deeplink || !isset($deeplink->click_through_url)) {
                // Generate new short and long links
                $website = Website::select('wid')->where("id", $apply->website_id)->first();
                $shortLink = $this->generateShortLink();
                $longLink = $this->generateLongLink($advertiser->sid, $website->wid, $subID, $landing_url);

                // Create a new deeplink tracking entry
                $deeplink = DeeplinkTracking::create([
                    "advertiser_id" => $advertiser->id,
                    "website_id" => $apply->website_id,
                    "publisher_id" => $user->publisher_id,
                    "tracking_url" => $shortLink,
                    "tracking_url_long" => $longLink,
                    "sub_id" => $subID,
                    "landing_url" => $landing_url,
                    "click_through_url" => ''
                ]);

                // Add the link generation job to the queue
                $queue = Vars::LINK_GENERATE;
                GenerateLinkModel::updateOrCreate([
                    'advertiser_id' => $deeplink->advertiser_id,
                    'publisher_id' => $deeplink->publisher_id,
                    'website_id' => $deeplink->website_id,
                    'landing_url' => $deeplink->landing_url,
                    'sub_id' => $deeplink->sub_id
                ], [
                    'name' => 'Deep Link Job',
                    'path' => 'DeeplinkGenerateJob',
                    'payload' => collect($deeplink->toArray()),
                    'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    'queue' => $queue
                ]);
            }

            return response()->json([
                "success" => true,
                "name" => $advertiser->name ?? null,
                "tracking_url" => $apply->tracking_url_short ?? null,
                "deeplink_enabled" => true,
                "deeplink_link_short_url" => $shortLink,
                "deeplink_link_url" => $deeplink->sub_id ? $deeplink->tracking_url_long : $deeplink->tracking_url
            ]);
        } else {
            return response()->json([
                "success" => false,
                "message" => "Please enter a valid advertiser URL."
            ]);
        }
    }
}
