<?php

namespace App\Traits\Publisher;

use App\Helper\Static\Methods;
use App\Models\TrackingDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait CustomClickGraph
{

    public function getCustomTopFiveClicks()
    {
        $startDate = Carbon::now()->format("Y-m-01 00:00:00");
        $endDate = Carbon::now()->format("Y-m-t 23:59:59");

        $advertiserIDz = Methods::getAdvertiserIDz($startDate, $endDate);

        return TrackingDetail::with("advertiser:id,sid,name")
            ->select(DB::raw('COUNT(id) as tracking', 'advertiser_id'), 'advertiser_id')
            ->where("publisher_id", auth()->user()->id)
            ->whereIn('advertiser_id', $advertiserIDz)
            ->where('website_id', auth()->user()->active_website_id)
            ->whereBetween("created_at", [Carbon::now()->subMonths(3)->format("Y-m-01 00:00:00"), Carbon::now()->format("Y-m-t 23:59:59")])
            ->groupBy("advertiser_id")
            ->orderBy('tracking', 'DESC')
            ->take(5)
            ->get();
    }

    public function getCustomClickCount($toDate, $fromDate, $user)
    {

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

    public function getCustomClickPercentage()
    {
        $subMonth = Methods::subMonths();
        $startDate = Carbon::now()->format("Y-m-01 00:00:00");
        $endDate = Carbon::now()->format("Y-m-t 23:59:59");

        $advertiserIDz = Methods::getAdvertiserIDz($startDate, $endDate);

        $currentMonthClick = TrackingDetail::select('tracking_id')
                                    ->whereIn('advertiser_id', $advertiserIDz)
                                    ->where('publisher_id', auth()->user()->id)
                                    ->where('website_id', auth()->user()->active_website_id)
                                    ->whereMonth('created_at', date('m'))
                                    ->count();

        $previousMonthClick = TrackingDetail::select('tracking_id')
                                    ->whereIn('advertiser_id', $advertiserIDz)
                                    ->where('publisher_id', auth()->user()->id)
                                    ->where('website_id', auth()->user()->active_website_id)
                                    ->whereMonth('created_at', $subMonth)
                                    ->count();

        return Methods::returnPerGrowth($previousMonthClick, $currentMonthClick);
    }

    public function setCustomPerformanceClick($toDate, $fromDate, $type, $user)
    {
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

        $clickCount = $trackingCount + $deeplinkTrackingCount + $couponTrackingCount;

        $queryDate = '%d';
        if($type == "day")
        {
            $date = "d";
        }
        elseif($type == "month")
        {
            $date = "M";
            $queryDate = '%b';
        }
        elseif($type == "year")
        {
            $date = "Y";
            $queryDate = '%Y';
        }

        $click = TrackingDetail::select(DB::raw("COUNT(tracking_id) daily_hits"), DB::raw("DATE_FORMAT(created_at, '{$queryDate}') as trans_date"));

        $click = $click
            ->whereBetween('created_at', [$toDate, $fromDate])
            ->where('publisher_id', auth()->user()->id)
            ->where('website_id', auth()->user()->active_website_id)
            ->groupBy('trans_date')
            ->orderBy('trans_date')
            ->get()
            ->pluck("daily_hits", "trans_date")
            ->toArray();

        $begin = new \DateTime($toDate);
        $end = new \DateTime($fromDate);

        $interval = \DateInterval::createFromDateString('1 ' . ucwords($type));
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            if(isset($click[$dt->format($date)]))
            {
                $click[$dt->format($date)] = $click[$dt->format($date)];
            }
            else
            {
                $click[$dt->format($date)] = 0;
            }
        }

        ksort($click);
        $click = array_filter(array_values($click));

        $minClick = $click ? min($click) : 1;
        $maxClick = $click ? floatval(max($click)) + 20 : 20;

        return [
            "count" => $clickCount,
            "click" => $click,
            "min_value" => $minClick,
            "max_value" => $maxClick,
        ];
    }

    public function getCustomCurrentDailyClick($toDate, $fromDate, $type)
    {
        $queryDate = '%d';
        if($type == "day")
        {
            $date = "d";
        }
        elseif($type == "month")
        {
            $date = "M";
            $queryDate = '%b';
        }
        elseif($type == "year")
        {
            $date = "Y";
            $queryDate = '%Y';
        }

        $tracking = TrackingDetail::select(DB::raw("COUNT(tracking_id) daily_hits"), DB::raw("DATE_FORMAT(created_at, '{$queryDate}') as trans_date"));

        $tracking = $tracking
            ->whereBetween('created_at', [$toDate, $fromDate])
            ->where('publisher_id', auth()->user()->id)
            ->where('website_id', auth()->user()->active_website_id)
            ->groupBy('trans_date')
            ->orderBy('trans_date')
            ->get()
            ->pluck("daily_hits", "trans_date")
            ->toArray();

        $begin = new \DateTime($toDate);
        $end = new \DateTime($fromDate);

        $interval = \DateInterval::createFromDateString('1 ' . ucwords($type));
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            if(isset($tracking[$dt->format($date)]))
            {
                $tracking[$dt->format($date)] = $tracking[$dt->format($date)];
            }
            else
            {
                $tracking[$dt->format($date)] = 0;
            }
        }

        return $tracking;

    }

    public function getCustomPreviousDailyClick()
    {
        $subMonth = Methods::subMonths();
        $startDate = $subMonth->format("Y-m-01 00:00:00");
        $endDate = $subMonth->format("Y-m-t 23:59:59");

        $advertiserIDz = Methods::getAdvertiserIDz($startDate, $endDate);

        $tracking = TrackingDetail::
            select(
                DB::raw("COUNT(tracking_id) daily_hits"),
                DB::raw('DATE_FORMAT(created_at, "%d") as trans_date')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('publisher_id', auth()->user()->id)
            ->where('website_id', auth()->user()->active_website_id)
            ->whereIn('advertiser_id', $advertiserIDz)
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
                $tracking[$dt->format("d")] = 0;
            }
        }

        ksort($tracking);

        return $tracking;

    }

}
