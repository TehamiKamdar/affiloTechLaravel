<?php

namespace App\Services\Admin\AdvertiserManagement\Api;

use App\Helper\Static\Vars;
use App\Models\Advertiser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class DuplicateService
{
    public function getDuplicateAdvertiserView(Request $request)
    {
        

        $networkMapping = [
            Vars::AWIN => Vars::AWIN,
            Vars::IMPACT_RADIUS => Vars::IMPACT_RADIUS,
            Vars::RAKUTEN => Vars::RAKUTEN,
            Vars::TRADEDOUBLER => Vars::TRADEDOUBLER,
            Vars::ADMITAD => Vars::ADMITAD,
            Vars::PARTNERIZE => Vars::PARTNERIZE,
            Vars::PEPPERJAM => Vars::PEPPERJAM,
            Vars::LINKCONNECTOR => Vars::LINKCONNECTOR,
            Vars::SALESGAIN => Vars::SALESGAIN,
            Vars::DUOMAI => Vars::DUOMAI,
        ];

        $networkPriority = [
            Vars::AWIN, Vars::IMPACT_RADIUS, Vars::RAKUTEN, Vars::TRADEDOUBLER, Vars::ADMITAD,
            Vars::PARTNERIZE, Vars::PEPPERJAM, Vars::LINKCONNECTOR,
             Vars::SALESGAIN, Vars::DUOMAI
        ];

        $duplicateUrls = Advertiser::select('url')
            ->selectRaw('COUNT(*) as count')
            ->whereNotNull('click_through_url')
            ->whereNotNull('url')
            ->groupBy('url')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('url');

// Fetch duplicate advertisers with optional status filtering
        $duplicatesQuery = Advertiser::whereIn('url', $duplicateUrls);

        if ($request->filled('filter')) {
            $filter = $request->filter === 'Yes' ? 1 : 0;
            $duplicatesQuery->where('status', $filter);
        }

        $duplicates = $duplicatesQuery->get();

// Group advertisers by URL
      $grouped = $duplicates->groupBy('url')->map(function ($group) use ($networkMapping, $networkPriority) {
    $firstAdvertiser = $group->first();

    $networkNames = $group->mapWithKeys(function ($advertiser) use ($networkMapping) {
        $sourceKey = $advertiser->source;
        $networkName = $networkMapping[$sourceKey] ?? $sourceKey;
        return [
            $networkName => [
                'id' => $advertiser->id,
                'name' => $networkName,
                'commission' => $advertiser->commission,
                'type' => $advertiser->commission_type,
                'status' => $advertiser->status,
            ],
        ];
    })->toArray();

    uksort($networkNames, function ($a, $b) use ($networkPriority) {
        $priorityA = array_search($a, $networkPriority);
        $priorityB = array_search($b, $networkPriority);
        return ($priorityA !== false ? $priorityA : PHP_INT_MAX) <=> ($priorityB !== false ? $priorityB : PHP_INT_MAX);
    });

    return [
        'name' => $firstAdvertiser->name,
        'url' => $firstAdvertiser->url,
        'network_names' => $networkNames,
    ];
})->filter(function ($advertiser) {
    return count($advertiser['network_names']) > 1;
})->values();

// Manual pagination
$page = request()->get('page', 1);
$perPage = 20;
$paginated = new LengthAwarePaginator(
    $grouped->forPage($page, $perPage),
    $grouped->count(),
    $perPage,
    $page,
    ['path' => request()->url(), 'query' => request()->query()]
);

return view('admin.advertiser.duplicate.advertiser', ['advertisers' => $paginated]);
    }

    public function storeDuplicateAdvertiserData(Request $request) {

        try {

            $data = [];

            if($request->id) {

                Advertiser::where("id", $request->id)->update([
                    "status" => $request->status,
                    "is_show" => $request->status,
                ]);

                $duplicates = Advertiser::select(['status', 'source'])->where("url", $request->url)->get();
                $source = [];
                foreach ($duplicates as $duplicate) {
                    if ($duplicate['status']) {
                        $source[] = $duplicate['source'];
                    }
                }
                $source = implode(', ', $source);
                $data = [
                    'source' => $source ? $source : "Not Assigned"
                ];
            }
            elseif ($request->url) {

                Advertiser::where("url", $request->url)->update([
                    "status" => Vars::ADVERTISER_NOT_AVAILABLE,
                    "is_show" => Vars::ADVERTISER_NOT_AVAILABLE
                ]);

                $data = [
                    'source' => 'Not Assigned'
                ];

            }

            $response = [
                "type" => "success",
                "message" => "Advertiser API Data Successfully Updated.",
                "data" => $data
            ];

        } catch (\Exception $exception) {

            $response = [
                "type" => "error",
                "message" => $exception->getMessage(),
                "data" => []
            ];

        }

        return response()->json($response);

    }
}
