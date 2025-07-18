<?php

namespace App\Traits\Publisher;

use App\Helper\Static\Methods;
use App\Models\CouponTrackingDetail;
use App\Models\DeeplinkTrackingDetail;
use App\Models\Role;
use App\Models\TrackingClick;
use App\Models\TrackingDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait ClickGraph
{

    public function getTopFiveClicks($user)
    {

        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;

        return Cache::remember("getTopFiveClicks{$userID}{$websiteID}", 60 * 60, function () use($user) {

            $startDate = Carbon::now()->subMonths(3)->startOfMonth()->toDateTimeString();
            $endDate = Carbon::now()->endOfMonth()->toDateTimeString();

            // Retrieve clicks for each model
            $clicksArr = $this->getClicks($user, $startDate, $endDate);

            // Aggregate the results by advertiser_id
            $aggregatedClicks = [];

            foreach ($clicksArr as $click) {
                $advertiserId = $click['advertiser_id'];
                if (!isset($aggregatedClicks[$advertiserId])) {
                    $aggregatedClicks[$advertiserId] = $click;
                } else {
                    $aggregatedClicks[$advertiserId]['tracking'] += $click['tracking'];
                }
            }

            // Convert the associative array to a numeric array
            $aggregatedClicks = array_values($aggregatedClicks);

            // Sort the aggregated results by tracking count in descending order
            usort($aggregatedClicks, function($a, $b) {
                return $b['tracking'] <=> $a['tracking'];
            });

            $aggregatedClicks = array_slice($aggregatedClicks, 0, 5);

            // Get the top 5 records
            return collect($aggregatedClicks);

        });
    }

    public function getClickCount($user)
    {
        $toDate = Carbon::now()->format("Y-m-01 00:00:00");
        $fromDate = Carbon::now()->format("Y-m-t 23:59:59");

        $totalRecords = DB::table('users')
            ->leftJoin('tracking_details', function($join) use ($toDate, $fromDate) {
                $join->on('users.active_website_id', '=', 'tracking_details.website_id')
                    ->whereBetween('tracking_details.created_at', [$toDate, $fromDate]);
            })
            ->leftJoin('deeplink_tracking_details', function($join) use ($toDate, $fromDate) {
                $join->on('users.active_website_id', '=', 'deeplink_tracking_details.website_id')
                    ->whereBetween('deeplink_tracking_details.created_at', [$toDate, $fromDate]);
            })
            ->leftJoin('coupon_tracking_details', function($join) use ($toDate, $fromDate) {
                $join->on('users.active_website_id', '=', 'coupon_tracking_details.website_id')
                    ->whereBetween('coupon_tracking_details.created_at', [$toDate, $fromDate]);
            })
            ->where('users.id', $user->id)
            ->where('users.active_website_id', $user->active_website_id)
            ->selectRaw('
                COUNT(DISTINCT tracking_details.id) as tracking_count,
                COUNT(DISTINCT deeplink_tracking_details.id) as deeplink_tracking_count,
                COUNT(DISTINCT coupon_tracking_details.id) as coupon_tracking_count
            ')
            ->first();

        $trackingCount = $totalRecords->tracking_count;
        $deeplinkTrackingCount = $totalRecords->deeplink_tracking_count;
        $couponTrackingCount = $totalRecords->coupon_tracking_count;

        $totalRecordsCount = $trackingCount + $deeplinkTrackingCount + $couponTrackingCount;

        return $totalRecordsCount;

    }

    public function getClickPercentage()
    {
        $startDate = Carbon::now()->format("Y-m-01 00:00:00");
        $endDate = Carbon::now()->format("Y-m-t 23:59:59");

        $currentMonthClick = TrackingDetail::select('tracking_id')->whereBetween('created_at', [$startDate, $endDate]);

        $user = auth()->user();
        if($user->getAllowed())
            $currentMonthClick = $currentMonthClick->where("publisher_id", auth()->user()->id)->where('website_id', auth()->user()->active_website_id);

        $currentMonthClick = $currentMonthClick->whereMonth('created_at', date('m'))->count();

        $previousMonthClick = TrackingDetail::select('tracking_id')
                                    ->whereBetween('created_at', [$startDate, $endDate]);

        if($user->getAllowed())
            $previousMonthClick = $previousMonthClick->where("publisher_id", auth()->user()->id)->where('website_id', auth()->user()->active_website_id);

        $previousMonthClick = $previousMonthClick->whereMonth('created_at', Methods::subMonths())->count();

        return Methods::returnPerGrowth($previousMonthClick, $currentMonthClick);
    }

    public function setPerformanceClick($user)
    {
        $click = $this->getClickCount($user);
        $dailyCurrentClick = $this->getCurrentDailyClick($user);
        $dailyCurrentClick = array_values($dailyCurrentClick);
        $dailyPreviousClick = $this->getPreviousDailyClick($user);
        $dailyPreviousClick = array_values($dailyPreviousClick);

        $getMinMaxClick = array_filter(array_merge($dailyCurrentClick, $dailyPreviousClick));
        $minClick = $getMinMaxClick ? min($getMinMaxClick) : 1;
        $maxClick = $getMinMaxClick ? floatval(max($getMinMaxClick)) + 20 : 20;
        $clickPercentage = $this->getClickPercentage($user);

        return [
            "count" => Methods::numberFormatShort($click),
            "min_value" => $minClick,
            "max_value" => $maxClick,
            "dailyCurrentMonth" => $dailyCurrentClick,
            "dailyPreviousMonth" => $dailyPreviousClick,
            ...$clickPercentage
        ];
    }

    public function getCurrentDailyClick($user)
    {

        $startDate = new \DateTime(now()->format("Y-m-01 00:00:00"));
        $endDate = new \DateTime(now()->format("Y-m-t 23:59:59"));

        $tracking = TrackingDetail::
            select(
                DB::raw("COUNT(tracking_id) daily_hits"),
                DB::raw('DATE_FORMAT(created_at, "%d") as trans_date')
            )
            ->whereMonth('created_at', date('m'))
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('publisher_id', $user->id)
            ->where('website_id', $user->active_website_id)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->orderBy('created_at')
            ->get()
            ->pluck("daily_hits", "trans_date")->toArray();

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($startDate, $interval, $endDate);

        foreach ($period as $dt) {
            if(!isset($tracking[$dt->format("d")]))
            {
                $tracking[$dt->format("d")] = 0.00;
            }
        }

        ksort($tracking);

        return $tracking;

    }

    public function getPreviousDailyClick($user)
    {
        $startDate = Methods::subMonths()->format("Y-m-01 00:00:00");
        $endDate = Methods::subMonths()->format("Y-m-t 23:59:59");

        $tracking = TrackingDetail::
            select(
                DB::raw("COUNT(tracking_id) daily_hits"),
                DB::raw('DATE_FORMAT(created_at, "%d") as trans_date')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('publisher_id', $user->id)
            ->where('website_id', $user->active_website_id)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%d-%m-%Y')"))
            ->orderBy('created_at')
            ->get()
            ->pluck("daily_hits", "trans_date")->toArray();

        $begin = new \DateTime($startDate);
        $end = new \DateTime($endDate);

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            if(!isset($tracking[$dt->format("d")]))
            {
                $tracking[$dt->format("d")] = 0.00;
            }
        }

        ksort($tracking);

        return $tracking;

    }

    // Function to get the clicks with common logic
    function getClicks($user, $startDate, $endDate) {
        $query = TrackingClick::with('advertiser:id,sid,name')
            ->select(DB::raw('SUM(total_clicks) as tracking'), 'advertiser_id');

        if ($user->getAllowed()) {
            $query = $query->where('publisher_id', $user->id)
                ->where('website_id', $user->active_website_id);
        }

        return $query->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('advertiser_id')
            ->orderBy('tracking', 'DESC')
            ->get()
            ->toArray();
    }
}
