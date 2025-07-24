<?php

namespace App\Services\Publisher\Advertiser;

use App\Models\Advertiser;
use App\Models\AdvertiserPublisher;
use App\Models\Country;
use App\Models\Mix;
use App\Models\User;
use App\Models\Website;
use App\Services\Publisher\RootService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class BaseService extends RootService
{
    public function advertiserLogic(Request $request)
    {
        $user = $request->user();


        // if ($user->status != User::STATUS_ACTIVE || !$user->is_completed) {
        //     $response = $this->userInactiveMsg($user);
        //     $response="User Profile is Not Completed";
        //     return $response;
        // }

        // if (empty($user->active_website_id) || $user->active_website_status != Website::ACTIVE) {
        //     $response = "User Website is not active yet";
        //     return $response;
        // }

        $perPage = $request->get('per_page', 50);
        $page = $request->get('page', 1);


        $cacheKey = 'advertisers_' . md5($request->path() . json_encode($request->all()) . $user->id);

        if ($request->get('is_update')) {
            $advertisers =  $this->findAdvertiserQuery($request, $user)->paginate($perPage, ['*'], 'page', $page);
        } else {
            $advertisers = Cache::remember($cacheKey, 1, function () use ($request, $user, $perPage, $page) {
                return $this->findAdvertiserQuery($request, $user)
                    ->paginate($perPage, ['*'], 'page', $page);
            });
        }




        $advertisersItems = collect($advertisers->items());

        $advertisersCheckboxValues = $advertisersItems->map(function ($item) {
            return [
                $item->id => ['name' => $item->name, 'source' => $item->source]
            ];
        })->collapse()->toArray();


        $from = ($advertisers->currentPage() - 1) * $advertisers->perPage() + 1;
        $to = min($advertisers->currentPage() * $advertisers->perPage(), $advertisers->total());

        if ($request->path() == 'publisher/new-advertisers') {
            if ($request->ajax()) {
                $response = response()->json([
                    'data' => view('publisher.advertisers.table.newajax', compact('advertisers'))->render(),
                    'pagination' => (string) $advertisers->links('partial.publisher_pagination'),
                    'to' => $to,
                    'from' => $from,
                    'total' => $advertisers->total(),
                    'advertisersCheckboxValues' => $advertisersCheckboxValues
                ]);
                return $response;
            }
        } else {
            if ($request->ajax()) {
                $response = response()->json([
                    'data' => view('publisher.advertisers.table.ajax', compact('advertisers'))->render(),
                    'pagination' => (string) $advertisers->links('partial.publisher_pagination'),
                    'to' => $to,
                    'from' => $from,
                    'total' => $advertisers->total(),
                    'advertisersCheckboxValues' => $advertisersCheckboxValues
                ]);
                return $response;
            }
        }


        $categories = Mix::where('type', Mix::CATEGORY)->get(['id', 'name']);
        $methods = Mix::where('type', Mix::PROMOTIONAL_METHOD)->get(['id', 'name']);
        $countries = Country::all(['name', 'iso2']);
        $defaultStatus = $this->defaultStatuses($request);


        return collect([
            'advertisers' => $advertisers,
            'pagination' => (string) $advertisers->links('partial.publisher_pagination'),
            'to' => $to,
            'from' => $from,
            'total' => $advertisers->total(),
            'categories' => $categories,
            'methods' => $methods,
            'countries' => $countries,
            'defaultStatus' => $defaultStatus,
            'advertisersCheckboxValues' => $advertisersCheckboxValues
        ]);
    }

    public function findAdvertiserQuery(Request $request, User $user)
    {



        if ($request->path() == 'publisher/new-advertisers') {
            $query = DB::table('advertisers')
                ->select([
                    'advertisers.id',
                    'advertisers.sid',
                    'advertisers.name',
                    'advertisers.primary_regions',
                    'advertisers.logo',
                    'advertisers.is_show',
                    'advertisers.url',
                    'advertisers.fetch_logo_url',
                    'advertisers.supported_regions',
                    'advertisers.is_fetchable_logo',
                    'advertisers.deeplink_enabled',
                    'advertisers.average_payment_time',
                    'advertisers.source',
                    'advertisers.commission',
                    'advertisers.commission_type',
                    'advertisers.created_at',
                    'advertiser_publishers.status',
                    'advertiser_publishers.locked_status',
                ])
                ->leftJoin("advertiser_publishers", function ($join) use ($user) {
                    $join->on("advertiser_publishers.advertiser_id", "=", "advertisers.id")
                        ->where("advertiser_publishers.publisher_id", "=", $user->publisher_id)
                        ->where("advertiser_publishers.website_id", "=", $user->active_website_id);
                })
                ->whereNull('advertiser_publishers.advertiser_id')->where('advertisers.is_active', 1)->where('advertisers.is_show', 1)->where('advertisers.created_at', '>=', \Carbon\Carbon::now()->subDays(15))->where('name', '!=', '')->where('name', '!=', null);
        } else {
            $query = DB::table('advertisers')
                ->select([
                    'advertisers.id',
                    'advertisers.sid',
                    'advertisers.name',
                    'advertisers.primary_regions',
                    'advertisers.logo',
                    'advertisers.url',
                    'advertisers.created_at',
                    'advertisers.fetch_logo_url',
                    'advertisers.supported_regions',
                    'advertisers.is_fetchable_logo',
                    'advertisers.deeplink_enabled',
                    'advertisers.average_payment_time',
                    'advertisers.source',
                    'advertisers.commission',
                    'advertisers.commission_type',
                    'advertiser_publishers.status',
                    'advertiser_publishers.locked_status',
                ])
                ->leftJoin('advertiser_publishers', function ($join) use ($user) {
                    $join->on('advertiser_publishers.advertiser_id', '=', 'advertisers.id')
                        ->where('advertiser_publishers.publisher_id', '=', $user->publisher_id)
                        ->where('advertiser_publishers.website_id', '=', $user->active_website_id);
                })
                ->where('advertisers.is_active', 1)->where('advertisers.is_show', 1)->where('name', '!=', '')->where('name', '!=', null);
        }
        $this->applyFilters($query, $request);
        // Return the ordered query
        return $query->orderBy('advertisers.name', 'ASC');
    }

    private function applyFilters($query, Request $request)
    {
        // Filter by country
        if ($request->filled('country')) {
            $country = $request->input('country');
            $query->where(function ($q) use ($country) {
                $q->where('advertisers.primary_regions', 'like', "%{$country}%")
                    ->orWhere('advertisers.primary_regions', 'like', '%00%');
            });
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('advertisers.name', 'like', "%{$search}%")
                    ->orWhere('advertisers.sid', 'like', "%{$search}%")
                    ->orWhere('advertisers.primary_regions', 'like', "%{$search}%");
            });
        }

        // Filter by advertiser type
        if ($request->filled('advertiser_type')) {
            $advertiserType = $request->input('advertiser_type');
            if ($advertiserType == Advertiser::THIRD_PARTY) {
                $query->where('advertisers.source', '!=', 'Quk')->where('advertisers.source', '!=', 'Eclick');
            }
        }


        // Apply status filters

        if ($request->ajax() || $request->has('status') || (!$request->has('status') && $request->routeIs('publisher.my-advertisers'))) {
            if ($request->path() != 'publisher/new-advertisers') {
                $this->applyStatusFilters($query, $request);
            }
        }
    }


    public function applyfor($query, Request $request) {}
    private function applyStatusFilters($query, Request $request)
    {
        $statusArray = [];

        // Check if 'status' is provided in the request
        if ($request->filled('status')) {
            $statusArray = is_string($request->input('status'))
                ? array_filter(explode(',', $request->input('status')))
                : $request->input('status');
        } else {
            // Default statuses for a specific route
            if (!$request->has('status') && $request->routeIs('publisher.my-advertisers')) {
                $statusArray = $this->defaultStatuses($request);
                $statusArray = array_keys($statusArray);
            }
        }

        $lockedStatus = [];
        $lockedTextStatus = [];

        // Handle 'active' status
        if (in_array('active', $statusArray)) {
            $lockedStatus[] = 1;
            $lockedTextStatus[] = 'active';
            $statusArray = array_diff($statusArray, ['active']);
        }

        // Handle 'locked' status
        if (in_array('locked', $statusArray)) {
            $lockedStatus[] = 0;
            $lockedTextStatus[] = 'locked';
            $statusArray = array_diff($statusArray, ['locked']);
        }

        // Get default statuses excluding selected and locked statuses
        $defaultStatusArray = array_keys($this->defaultStatuses($request));
        $defaultStatusArray = array_diff($defaultStatusArray, $statusArray, $lockedTextStatus);

        // Apply filters to the query
        $query->where(function ($q) use ($statusArray, $lockedStatus, $defaultStatusArray) {
            $q->when(count($statusArray), function ($q) use ($statusArray) {
                $q->whereIn('advertiser_publishers.status', $statusArray);
            })
                ->when(count($defaultStatusArray), function ($q) use ($defaultStatusArray) {
                    $q->whereNotIn('advertiser_publishers.status', $defaultStatusArray);
                })
                ->when(in_array(1, $lockedStatus) && in_array(0, $lockedStatus), function ($q) {
                    $q->orWhereIn('advertisers.status', [0, 1]);
                })
                ->when(in_array(1, $lockedStatus), function ($q) {
                    $q->orWhere('advertisers.status', 1);
                })
                ->when(in_array(0, $lockedStatus), function ($q) {
                    $q->orWhereNull('advertiser_publishers.locked_status');
                });
        });
    }





    public function defaultStatuses(Request $request)
    {
        // Define default statuses
        $statuses = ["active", "pending", "joined", "hold", "rejected"];

        // Determine the route name
        $routeName = $request->route_name ?? $request->route()->getName();

        // Modify statuses based on route
        if ($routeName === "publisher.my-advertisers") {
            unset($statuses[0], $statuses[1]); // Remove "locked", "active", "pending"
        }

        $statusArray = [];
        $selectedStatuses = [];

        // Check if 'status' is provided in the request
        if ($request->filled('status')) {
            $selectedStatuses = is_string($request->input('status'))
                ? array_filter(explode(',', $request->input('status')))
                : $request->input('status');
        }

        // Use default statuses if none are selected
        if (empty($selectedStatuses)) {
            $selectedStatuses = $statuses;
        }

        // Build the final status array
        foreach ($statuses as $status) {
            $statusArray[$status] = in_array($status, $selectedStatuses);
        }

        return $statusArray;
    }
}
