<?php
namespace App\Services\Admin\Approval;

use App\Helper\Vars;
use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\FetchDailyData;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Services\Admin\PublisherManagement\Apply\MiscService;

class IndexService
{
    
    protected $service;
    public function __construct(MiscService $service)
    {
        $this->service = $service;
    }
    public function init(Request $request, $status)
    {
        $routeName = $request->path();
        $statusMap = [
            "admin/advertisers/approval/pending" => [
                'title' => "Approval Request Pending",
                'api_title' => AdvertiserPublisher::STATUS_PENDING
            ],
            "admin/advertisers/approval/joined" => [
                'title' => "Approval Request Joined",
                'api_title' => AdvertiserPublisher::STATUS_ACTIVE
            ],
            "admin/advertisers/approval/hold" => [
                'title' => "Approval Request Hold",
                'api_title' => AdvertiserPublisher::STATUS_HOLD
            ],
            "admin/advertisers/approval/rejected" => [
                'title' => "Approval Request Rejected",
                'api_title' => AdvertiserPublisher::STATUS_REJECTED
            ],
            // "admin/advertisers/approval/joined" => "Advertisers"
        ];

        $title = $statusMap[$routeName] ?? "";

        return collect(['title' => $title]);
    }

    public function ajax(Request $request)
    {
        try {
           
$data = AdvertiserPublisher::select([
        'id', 'advertiser_id', 'publisher_id', 'website_id', 
        'created_at', 'source', 'applied_at'
    ])
    ->with(["advertiser:id,sid,name", "publisher:publisher_id,name", "website:id,name"])
    ->where("status", $request->status);

// Apply search filter only if a search term exists
if (!empty($request->search['value'])) {
    $searchTerm = $request->search['value'];

    $data->where(function($query) use ($searchTerm) {
        $query->whereHas('advertiser', function($subQuery) use ($searchTerm) {
            $subQuery->where('name', 'like', "%{$searchTerm}%");
        })
        ->orWhereHas('publisher', function($subQuery) use ($searchTerm) {
            $subQuery->where('name', 'like', "%{$searchTerm}%");
        })
        ->orWhereHas('website', function($subQuery) use ($searchTerm) {
            $subQuery->where('name', 'like', "%{$searchTerm}%");
        });
    });
}
if (!empty($request->source)) {
    $searchTerm = $request->source;
if($searchTerm != 'All'){
     $data->where(function($query) use ($searchTerm) {
        $query->whereHas('advertiser', function($subQuery) use ($searchTerm) {
            $subQuery->where('source', 'like', "%{$searchTerm}%");
        });
    });
}
   
}

            return DataTables::of($data)
                ->setRowId(function($row) {
                    return $row['id']; // Adding a prefix to the row id for better readability
                })
                ->addColumn('advertiser_name', function($row) {
                    return $row['advertiser']['name'] ?? '-';
                })
                ->addColumn('advertiser_sid', function($row) {
                    return $row['advertiser']['id'] ?? '-';
                })
                ->addColumn('publisher_name', function($row) {
                    return $row['publisher']['name'] ?? '-';
                })
                ->addColumn('publisher_website', function($row) {
                    return $row['website']['name'] ?? '-';
                })
                ->editColumn('source', function ($row) {
                    $source = ucwords(strtolower($row['source']));
                    return "<div class='text-center'>{$source}</div>";
                })
                ->addColumn('action', function($row) {
                    $viewUrl = route("admin.advertisers.api.view", ['advertiser' => $row['advertiser_id']]);
                    $deleteUrl = ""; // Can add the delete URL if required

                    return '<a href="'.$viewUrl.'" class="btn btn-sm btn-glow-primary btn-primary">View</a>';
                })
                ->rawColumns(['action', 'url', 'is_tracking_url', 'source', 'provider_status'])
                ->make(true);

        } catch (\Exception $exception) {
            // Consider logging the error instead of using dd() to display it
            \Log::error('Error in ajax method: ' . $exception->getMessage());
            return response()->json(['error' => $exception->getMessage()], 500);
        }
    }

    public function updateStatus(Request $request)
    {
        
        $advertiserIDz = is_array($request->advertiser_idz) ? $request->advertiser_idz : [$request->advertiser_idz];
        $advertiserPublishers = AdvertiserPublisher::whereIn('id', $advertiserIDz)->get();
        
         $request->a_id = $advertiserIDz;
 return $this->service->updateAdvertiserStatus($request);
        // foreach ($advertiserPublishers as $advertiserPublisher) {
        //     FetchDailyData::updateOrCreate(
        //         [
        //             'path' => 'DataAdvertiserPublisherStatucChange',
        //             'process_date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
        //             'date' => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
        //             'key' => $request->status,
        //             'advertiser_id' => $advertiserPublisher->advertiser_id,
        //             'publisher_id' => $advertiserPublisher->publisher_id,
        //             'website_id' => $advertiserPublisher->website_id,
        //             'source' => $advertiserPublisher->source,
        //             'queue' => Vars::EXTRA_WORK,
        //             'source' => Vars::GLOBAL, // Check if this is correct, as source is defined twice
        //             'date' => now()->toDateString()
        //         ],
        //         [
        //             'name' => 'Advertiser Publisher Status Update',
        //             'payload' => json_encode([
        //                 'id' => $advertiserPublisher->id,
        //                 'advertiser_id' => $advertiserPublisher->advertiser_id,
        //                 'publisher_id' => $advertiserPublisher->publisher_id,
        //                 'website_id' => $advertiserPublisher->website_id,
        //                 'source' => $advertiserPublisher->source,
        //                 'status' => $request->status,
        //                 'message' => $request->message
        //             ]),
        //             'status' => Vars::JOB_STATUS_IN_PROCESS,
        //             'is_processing' => Vars::JOB_NOT_PROCESS,
        //             'type' => Vars::ADVERTISER
        //         ]
        //     );
        // }
    }
}
