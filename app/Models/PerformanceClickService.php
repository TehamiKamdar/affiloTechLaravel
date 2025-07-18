<?php

namespace App\Service\Publisher\Reports;

use App\Enums\ExportType;
use App\Exports\PerformanceExport;
use App\Helper\Static\Vars;
use Illuminate\Support\Facades\Cache;
use App\Models\Website;
use App\Traits\Publisher\ClickGraph;
use App\Traits\Publisher\CommissionGraph;
use App\Traits\Publisher\CustomClickGraph;
use App\Traits\Publisher\CustomCommissionGraph;
use App\Traits\Publisher\CustomPerformanceClickGraph;
use App\Traits\Publisher\CustomPerformanceGraph;
use App\Traits\Publisher\CustomSalesGraph;
use App\Traits\Publisher\CustomTransactionGraph;
use App\Traits\Publisher\PerformanceGraph;
use App\Traits\Publisher\SalesGraph;
use App\Traits\Publisher\TransactionGraph;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class PerformanceClickService
{
    use CommissionGraph, CustomCommissionGraph, TransactionGraph, CustomTransactionGraph, PerformanceGraph, CustomPerformanceGraph, CustomPerformanceClickGraph, SalesGraph, CustomSalesGraph, ClickGraph, CustomClickGraph;

    public function list(Request $request)
    {
        $user = auth()->user();
        $websitesCount = Website::whereHas('users', function ($query) use ($user) {
            $query->where('id', $user->id);
        })
            ->where('status', Website::ACTIVE)
            ->count();

        if ($websitesCount === 0) {
            $url = route("publisher.profile.websites.index");
            Session::put("error", "Please go to <a href='{$url}'>website settings</a> and verify your site to view Report Performance.");
        }

        if ($user->status !== "active" || $websitesCount === 0) {
            return view("template.publisher.reports.performance.click.list", [
                'performanceOverviewList2' => collect(),
                'total' => 0,
                'total2' => 0,
                'performanceOverview' => []
            ]);
        }

        // Define date format for display
        $dateFormat = 'Y-m-d';
        $queryDateFormat = '%Y-%m-%d';

        // Determine date ranges
        $startDateParam = $request->input('start_date', '');
        $endDateParam = $request->input('end_date', '');

        if($startDateParam && $endDateParam) {
            $currentPeriodStart = Carbon::parse($startDateParam);
            $currentPeriodEnd = Carbon::parse($endDateParam);

            $differenceInDays = $currentPeriodStart->diffInDays($currentPeriodEnd);

            $differenceInDays = $differenceInDays <= 0 ? 1 : $differenceInDays;

            $previousPeriodStart = $currentPeriodStart->copy()->subDays($differenceInDays);
            $previousPeriodEnd = $currentPeriodEnd->copy()->subDays($differenceInDays);

        } else
        {
            $currentPeriodStart = Carbon::parse($startDateParam)->firstOfMonth();
            $currentPeriodEnd = Carbon::parse($endDateParam)->lastOfMonth();
            $previousPeriodStart = $currentPeriodStart->copy()->subMonth()->firstOfMonth();
            $previousPeriodEnd = $currentPeriodEnd->copy()->subMonth()->lastOfMonth();
        }

        // Get data for current and previous periods
        $currentPeriodData = $this->getData($currentPeriodStart, $currentPeriodEnd, $user, $queryDateFormat);
        $previousPeriodData = $this->getData($previousPeriodStart, $previousPeriodEnd, $user, $queryDateFormat);

        // Fill missing dates for both periods
        $currentPeriodData = $this->fillMissingDates($currentPeriodData, $currentPeriodStart, $currentPeriodEnd, $dateFormat);
        $previousPeriodData = $this->fillMissingDates($previousPeriodData, $previousPeriodStart, $previousPeriodEnd, $dateFormat);

        // Prepare performance overview data
        $performanceOverview = $this->preparePerformanceOverview($currentPeriodData, $previousPeriodData);

        // Retrieve advertiser data
        $searchByName = $request->input('conversion_search', '');

        $limit = Vars::DEFAULT_PUBLISHER_CONVERSION_PERFORMANCE_PAGINATION;
        if(session()->has('publisher_click_performance_limit')) {
            $limit = session()->get('publisher_click_performance_limit');
        }

        $performanceOverviewList2 = $this->fetchAdvertiserData($currentPeriodStart, $currentPeriodEnd, $user, $searchByName, $limit);

        $total = $performanceOverviewList2->total();

        // Return view or JSON response based on request type
        if ($request->ajax()) {
            $view = view("template.publisher.reports.performance.click.list_view", compact('performanceOverviewList2'))->render();
            return response()->json([
                'total' => $total,
                'total2' => 0, // This value needs clarification
                'html' => $view,
                'performanceOverview' => $performanceOverview
            ]);
        }

        return view("template.publisher.reports.performance.click.list", compact('performanceOverview', 'performanceOverviewList2', 'total'));
    }

    private function preparePerformanceOverview($currentPeriodData, $previousPeriodData)
    {
        // Prepare data for rendering
        $currentPeriodLabels = array_keys($currentPeriodData);
        $currentPeriodValues = array_values($currentPeriodData);
        $previousPeriodLabels = array_keys($previousPeriodData);
        $previousPeriodValues = array_values($previousPeriodData);

        // Calculate min and max clicks
        $currentMinClick = min($currentPeriodValues ?: [0]);
        $currentMaxClick = max($currentPeriodValues ?: [0]) + 20;
        $previousMinClick = min($previousPeriodValues ?: [0]);
        $previousMaxClick = max($previousPeriodValues ?: [0]) + 20;

        // Calculate total clicks for current and previous periods
        $currentPeriodTotal = array_sum($currentPeriodValues);
        $previousPeriodTotal = array_sum($previousPeriodValues);

        // Calculate growth percentage
        $growthPercentage = $previousPeriodTotal > 0 ? (($currentPeriodTotal - $previousPeriodTotal) / $previousPeriodTotal) * 100 : 0;
        $growthPercentage = max(-100, min(100, $growthPercentage));

        // Prepare performance overview data
        return [
            "currentPeriod" => [
                "count" => $currentPeriodTotal,
                "click" => $currentPeriodValues,
                "min_value" => $currentMinClick,
                "max_value" => $currentMaxClick,
                "labels" => $currentPeriodLabels
            ],
            "previousPeriod" => [
                "count" => $previousPeriodTotal,
                "click" => $previousPeriodValues,
                "min_value" => $previousMinClick,
                "max_value" => $previousMaxClick,
                "labels" => $previousPeriodLabels
            ],
            "growth" => [
                "percentage" => $growthPercentage,
                "position" => $growthPercentage > 0 ? "up" : "down"
            ]
        ];
    }

    public function getData($startDate, $endDate, $user, $queryDateFormat)
    {
        $trackingDetails = DB::table('tracking_clicks')
            ->selectRaw("SUM(total_clicks) as total_clicks, DATE_FORMAT(date, '{$queryDateFormat}') as trans_date")
            ->whereBetween('date', [Carbon::parse($startDate)->toDateString(), Carbon::parse($endDate)->toDateString()])
            ->where('publisher_id', $user->id)
            ->where('website_id', $user->active_website_id);

        return $trackingDetails
            ->groupBy('trans_date')
            ->orderBy('trans_date')
            ->get()
            ->groupBy('trans_date')
            ->map(function ($group) {
                return $group->sum('total_clicks');
            })
            ->toArray();

    }

    public function getAdvertiserData($startDate, $endDate, $user, $searchByName = null, $limit = 10)
    {

        $today = Carbon::today()->format('Y-m-d');

        if ($startDate <= $today && $endDate >= $today) {
            // Fetch fresh data for today if within the range
            return $this->fetchAdvertiserData($startDate, $endDate, $user, $searchByName, $limit, $today);
        }

        // Use cached data for historical records
        return $this->fetchAdvertiserData($startDate, $endDate, $user, $searchByName, $limit);
    }

    private function fetchAdvertiserData($startDate, $endDate, $user, $searchByName = null, $limit = 10)
    {
        $result = DB::table('tracking_clicks')
            ->select(
                'advertisers.sid as advertiser_sid',
                'advertisers.name as advertiser_name',
                DB::raw("SUM(tracking_clicks.total_clicks) as total_clicks"),
                DB::raw("SUM(CASE WHEN tracking_clicks.link_type = 'tracking' THEN tracking_clicks.total_clicks ELSE 0 END) as tracking_total_clicks"),
                DB::raw("SUM(CASE WHEN tracking_clicks.link_type = 'deeplink' THEN tracking_clicks.total_clicks ELSE 0 END) as deeplink_total_clicks"),
                DB::raw("SUM(CASE WHEN tracking_clicks.link_type = 'coupon' THEN tracking_clicks.total_clicks ELSE 0 END) as coupon_total_clicks")
            )
            ->join('advertisers', 'advertisers.id', '=', 'tracking_clicks.advertiser_id')
            ->whereBetween('tracking_clicks.date', [Carbon::parse($startDate)->toDateString(), Carbon::parse($endDate)->toDateString()])
            ->where('tracking_clicks.publisher_id', $user->id)
            ->where('tracking_clicks.website_id', $user->active_website_id);

        if ($searchByName) {
            $result = $result->where('advertisers.name', 'LIKE', "%$searchByName%");
        }

        return $result->groupBy('advertisers.sid', 'advertisers.name')
            ->orderBy('total_clicks', 'DESC')
            ->paginate($limit);

        // Include today's data if within the range
//        if ($today) {
//            $todayData = DB::table('tracking_clicks')
//                ->select('advertisers.sid as advertiser_sid', 'advertisers.name as advertiser_name', DB::raw("SUM(tracking_clicks.total_clicks) as total_clicks"))
//                ->join('advertisers', 'advertisers.id', '=', 'tracking_clicks.advertiser_id')
//                ->whereDate('tracking_clicks.date', $today);
//
//            if($searchByName)
//                $todayData = $todayData->where("advertisers.name", 'LIKE', "%$searchByName%");
//
//            $todayData = $todayData->groupBy('advertiser_sid')
//                                    ->orderBy('total_clicks', 'DESC')
//                                    ->get();
//
//            // Merge today's data with existing result
//            $result->getCollection()->each(function ($item) use ($todayData) {
//                $todayClicks = $todayData->firstWhere('advertiser_sid', $item->advertiser_sid);
//                if ($todayClicks) {
//                    $item->total_clicks += $todayClicks->total_clicks;
//                }
//            });
//        }

        return $result;
    }

    public function fillMissingDates($data, $startDate, $endDate, $dateFormat)
    {
        $begin = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end->modify('+1 day'));

        foreach ($period as $dt) {
            $formattedDate = $dt->format($dateFormat);
            if (!isset($data[$formattedDate])) {
                $data[$formattedDate] = 0;
            }
        }

        ksort($data);
        return $data;
    }

    public function export(ExportType $type, Request $request)
    {
        $startDate = $request->start_date ?? now()->format("Y-m-01 00:00:00");
        $endDate = $request->end_date ?? now()->format("Y-m-t 23:59:59");

        if(ExportType::CSV == $type)
            return Excel::download(new PerformanceExport($startDate, $endDate), 'transactions.csv');
        elseif(ExportType::XLSX == $type)
            return Excel::download(new PerformanceExport($startDate, $endDate), 'transactions.xlsx');
    }
}
