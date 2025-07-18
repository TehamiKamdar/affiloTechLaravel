<?php

namespace App\Services\Publisher\Dashboard;

use App\Helper\Methods;
use App\Models\Country;
use App\Models\User;
use App\Models\Website;
use App\Models\Advertiser;
use App\Models\TrackingClick;
use App\Services\Publisher\RootService;
use Illuminate\Http\Request;
use App\Models\Transaction;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class IndexService extends RootService
{
    public function init(Request $request)
    {
        $user = $request->user();

        // Check if the user is inactive
        if (auth()->user()->status != User::STATUS_ACTIVE && auth()->user()->is_completed) {
            $this->userInactiveMsg($user);
        }
        // Check if the user's website is inactive
        else if (empty($user->active_website_id) || $user->active_website_status != Website::ACTIVE) {
            $this->websiteInactiveMsg($user);
        }

        $publisher_id = $user->publisher_id;

        // Fetch categories and active countries
        $categories = Methods::getCategories();
        $countries = Country::where('status', 'active')->get();


        // Set the SEO title
        seo()->title(default: 'Dashboard — ' . env("APP_NAME"));
        // Dashboard title and headings
        $title = "Dashboard";
        $headings = [
            '',
            $title
        ];

        $transactions = Transaction::where('publisher_id', $user->publisher_id)->orderBy('transaction_date', 'desc')->limit(5)->get();



        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();

        // Get last month range
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();

        // Current Month Totals
        $currentApprovedTotal = Transaction::where('commission_status', 'approved')
            ->whereBetween('transaction_date', [$currentMonthStart, $currentMonthEnd])
            ->where('publisher_id', $user->publisher_id)->sum('commission_amount');

        $currentPendingTotal = Transaction::where('commission_status', 'pending')
            ->whereBetween('transaction_date', [$currentMonthStart, $currentMonthEnd])
            ->where('publisher_id', $user->publisher_id)->sum('commission_amount');

        $currentDeclinedTotal = Transaction::where('commission_status', 'declined')
            ->whereBetween('transaction_date', [$currentMonthStart, $currentMonthEnd])
            ->where('publisher_id', $user->publisher_id)->sum('commission_amount');

        $currentRejectedTotal = Transaction::where('commission_status', 'rejected')
            ->whereBetween('transaction_date', [$currentMonthStart, $currentMonthEnd])
            ->where('publisher_id', $user->publisher_id)->sum('commission_amount');

        // $currentRejectedTotal = Transaction::whereBetween('transaction_ate', [$currentMonthStart, $currentMonthEnd])
        //     ->where(column: 'publisher_id',$user->publisher_id)->sum('commission_amount');

        $currentTotalSales = Transaction::whereBetween('transaction_date', [$currentMonthStart, $currentMonthEnd])
            ->where('publisher_id', $user->publisher_id)->sum('received_sale_amount');

        // Last Month Totals
        $lastApprovedTotal = Transaction::where('commission_status', 'approved')
            ->whereBetween('transaction_date', [$lastMonthStart, $lastMonthEnd])
            ->where('publisher_id', $user->publisher_id)->sum('commission_amount');

        $lastPendingTotal = Transaction::where('commission_status', 'pending')
            ->whereBetween('transaction_date', [$lastMonthStart, $lastMonthEnd])
            ->where('publisher_id', $user->publisher_id)->sum('commission_amount');

        $lastDeclinedTotal = Transaction::where('commission_status', 'declined')
            ->whereBetween('transaction_date', [$lastMonthStart, $lastMonthEnd])
            ->where('publisher_id', $user->publisher_id)->sum('commission_amount');

        $lastRejectedTotal = Transaction::where('commission_status', 'declined')
            ->whereBetween('transaction_date', [$lastMonthStart, $lastMonthEnd])
            ->where('publisher_id', $user->publisher_id)->sum('commission_amount');

        // $lastRejectedTotal = Transaction::whereBetween('transaction_date', [$lastMonthStart, $lastMonthEnd])
        //     ->where('publisher_id',$user->publisher_id)->sum('commission_amount');

        $lastTotalSales = Transaction::whereBetween('transaction_date', [$lastMonthStart, $lastMonthEnd])
            ->where('publisher_id', $user->publisher_id)->sum('received_sale_amount');

        // Comparison (Percentage Change)
        function percentageChange($current, $previous)
        {
            if ($previous == 0) return $current > 0 ? 100 : 0; // Avoid division by zero
            return round((($current - $previous) / $previous) * 100, 2);
        }

        $approvedChange = percentageChange($currentApprovedTotal, $lastApprovedTotal);
        $pendingChange = percentageChange($currentPendingTotal, $lastPendingTotal);
        $declinedChange = percentageChange($currentDeclinedTotal, $lastDeclinedTotal);
        $rejectedChange = percentageChange($currentRejectedTotal, $lastRejectedTotal);
        $totalSalesChange = percentageChange($currentTotalSales, $lastTotalSales);

        $approvedTotal = Transaction::where('commission_status', 'approved')->where('publisher_id', $user->publisher_id)->sum('commission_amount');
        $pendingTotal = Transaction::where('commission_status', 'pending')->where('publisher_id', $user->publisher_id)->sum('commission_amount');
        $declinedTotal = Transaction::where('commission_status', 'declined')->where('publisher_id', $user->publisher_id)->sum('commission_amount');
        $rejectedTotal = Transaction::where('commission_status', 'rejected')->where('publisher_id', $user->publisher_id)->sum('commission_amount');
        //  $rejectedTotal = Transaction::where('publisher_id',$user->publisher_id)->sum('commission_amount');
        $Total = Transaction::where('publisher_id', $user->publisher_id)->sum('received_sale_amount');


        // Fetch sales data grouped by date for the current month
        $topSales = $this->getTopFiveSales($user);

        $advertisers = Advertiser::query()
            ->select([
                'advertisers.id',
                'advertisers.sid',
                'advertisers.name',
                'advertisers.primary_regions',
                'advertisers.logo',
                'advertisers.is_fetchable_logo',
                'advertisers.fetch_logo_url',
                'advertisers.supported_regions',
                'advertisers.source',
                'advertisers.created_at',
                'advertisers.commission as commission',
                'advertisers.commission_type',
                'advertisers.status AS adv_status'
            ])
            ->leftJoin("advertiser_publishers", function ($join) use ($user) {
                $join->on("advertiser_publishers.advertiser_id", "=", "advertisers.id")
                    ->where("advertiser_publishers.publisher_id", "=", $user->publisher_id)
                    ->where("advertiser_publishers.website_id", "=", $user->active_website_id);
            })->whereNull('advertiser_publishers.advertiser_id')->where('advertisers.name','!=',null)->where('advertisers.name','!=','')->latest()->take(12)->get();

        $data = [
            'currentMonthStart'     => $currentMonthStart,
            'currentMonthEnd'       => $currentMonthEnd,
            'lastMonthStart'        => $lastMonthStart,
            'lastMonthEnd'          => $lastMonthEnd,
            'currentApprovedTotal'  => $currentApprovedTotal,
            'currentPendingTotal'   => $currentPendingTotal,
            'currentDeclinedTotal'  => $currentDeclinedTotal,
            'currentRejectedTotal'  => $currentRejectedTotal,
            'currentTotalSales'     => $currentTotalSales,
            'lastApprovedTotal'     => $lastApprovedTotal,
            'lastPendingTotal'      => $lastPendingTotal,
            'lastDeclinedTotal'     => $lastDeclinedTotal,
            'lastRejectedTotal'     => $lastRejectedTotal,
            'lastTotalSales'        => $lastTotalSales,
            'approvedChange'        => $approvedChange,
            'pendingChange'         => $pendingChange,
            'declinedChange'        => $declinedChange,
            'rejectedChange'        => $rejectedChange,
            'totalSalesChange'      => $totalSalesChange,
            'approvedTotal'         => $approvedTotal,
            'pendingTotal'          => $pendingTotal,
            'declinedTotal'         => $declinedTotal,
            'rejectedTotal'         => $rejectedTotal,
            'Total'                 => $Total,
        ];

        // Return the view with the necessary data
        // return $user;
        return view('home', compact('publisher_id','topSales','advertisers', 'categories','countries','title', 'headings', 'approvedTotal', 'pendingTotal', 'declinedTotal', 'rejectedTotal', 'Total', 'approvedChange', 'pendingChange', 'declinedChange', 'rejectedChange', 'totalSalesChange', 'transactions'));
    }

    public function getTopFiveSales($user)
{

        $transactions = Transaction::selectRaw('
                SUM(sale_amount) as total_sales_amount,
                transactions.advertiser_name,
                transactions.advertiser_id,
                transactions.sale_amount_currency,
                transactions.external_advertiser_id,
                advertisers.fetch_logo_url,
                advertisers.logo
            ')
            ->leftJoin('advertisers', 'advertisers.advertiser_id', '=', 'transactions.advertiser_id')->where('transactions.publisher_id',$user->publisher_id);



        return $transactions
            ->groupBy([
                'transactions.advertiser_name',
                'transactions.advertiser_id',
                'transactions.sale_amount_currency',
                'transactions.external_advertiser_id',
                'advertisers.fetch_logo_url',
                'advertisers.logo'
            ])
            ->orderByDesc('total_sales_amount')
            ->take(8)
            ->get();

}


    public function chart_data(Request $request)
    {
        $user = auth()->user();
        $currentMonth = Carbon::now()->format('Y-m');
        $previousMonth = Carbon::now()->subMonth()->format('Y-m');

        // Get all dates for the current and previous month
        $currentMonthDates = $this->generateDateRange(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth());
        $previousMonthDates = $this->generateDateRange(Carbon::now()->subMonth()->startOfMonth(), Carbon::now()->subMonth()->endOfMonth());

        if ($request->type == 'sales') {
            // Fetch actual sales data
            $currentMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(received_sale_amount) as total')
                ->where('transaction_date', 'like', "$currentMonth%")
                ->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date'); // Convert collection to key-value pair

            $previousMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(received_sale_amount) as total')
                ->where('transaction_date', 'like', "$previousMonth%")
                ->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');
        } else if ($request->type == 'approved') {
            $currentMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(commission_amount) as total')
                ->where('transaction_date', 'like', "$currentMonth%")
                ->where('commission_status', 'approved')->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date'); // Convert collection to key-value pair

            $previousMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(commission_amount) as total')
                ->where('transaction_date', 'like', "$previousMonth%")
                ->where('commission_status', 'approved')->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');
        } else if ($request->type == 'pending') {
            $currentMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(commission_amount) as total')
                ->where('transaction_date', 'like', "$currentMonth%")
                ->where('commission_status', 'pending')->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date'); // Convert collection to key-value pair

            $previousMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(commission_amount) as total')
                ->where('transaction_date', 'like', "$previousMonth%")
                ->where('commission_status', 'pending')->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');
        } else if ($request->type == 'rejected') {
            $currentMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(commission_amount) as total')
                ->where('transaction_date', 'like', "$currentMonth%")
                ->where('commission_status', 'declined')->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date'); // Convert collection to key-value pair

            $previousMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(commission_amount) as total')
                ->where('transaction_date', 'like', "$previousMonth%")
                ->where('commission_status', 'declined')->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');
        } else if ($request->type == 'tracked') {
            $currentMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(commission_amount) as total')
                ->where('transaction_date', 'like', "$currentMonth%")->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date'); // Convert collection to key-value pair

            $previousMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(commission_amount) as total')
                ->where('transaction_date', 'like', "$previousMonth%")->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');
        }


        // Fill missing dates with 0
        $formattedCurrentMonthSales = [];
        foreach ($currentMonthDates as $date) {
            $formattedCurrentMonthSales[] = [
                'date' => $date,
                'total' => isset($currentMonthSales[$date]) ? round($currentMonthSales[$date]->total, 2) : 0
            ];
        }

        $formattedPreviousMonthSales = [];
        foreach ($previousMonthDates as $date) {
            $formattedPreviousMonthSales[] = [
                'date' => $date,
                'total' => isset($previousMonthSales[$date]) ? round($previousMonthSales[$date]->total, 2) : 0
            ];
        }

        return response()->json([
            'currentMonth' => $formattedCurrentMonthSales,
            'previousMonth' => $formattedPreviousMonthSales,
            'type' => $request->type,
            'color' => $request->color,
            'totals' => [
                'sales' => $this->getTotal('sales', $user, $currentMonth),
                'approved' => $this->getTotal('approved', $user, $currentMonth),
                'pending' => $this->getTotal('pending', $user, $currentMonth),
                'rejected' => $this->getTotal('rejected', $user, $currentMonth),
            ]
        ]);
    }
    private function getTotal($type, $user, $month)
    {
        $query = Transaction::where('transaction_date', 'like', "$month%")
            ->where('publisher_id', $user->publisher_id);

        if ($type === 'sales') {
            return round($query->sum('received_sale_amount'), 2);
        }

        if ($type === 'approved') {
            return round($query->where('commission_status', 'approved')->sum('commission_amount'), 2);
        }

        if ($type === 'pending') {
            return round($query->where('commission_status', 'pending')->sum('commission_amount'), 2);
        }

        if ($type === 'rejected') {
            return round($query->where('commission_status', 'declined')->sum('commission_amount'), 2);
        }

        return 0;
    }


    public function performance_report(Request $request)
    {

        if ($request->type == 'transaction') {
            $topAdvertisers = DB::table('transactions')
                ->join('advertisers', 'transactions.internal_advertiser_id', '=', 'advertisers.id')
                ->select('advertisers.name', DB::raw('SUM(transactions.received_sale_amount) as total_sales'))
                ->where('transactions.advertiser_country', $request->country)
                ->groupBy('advertisers.name')
                ->orderByDesc('total_sales')
                ->limit(5)
                ->get();

            return response()->json([
                'labels' => $topAdvertisers->pluck('name'), // X-axis labels
                'data' => $topAdvertisers->pluck('total_sales')  // Y-axis values
            ]);
        }
    }

    private function generateDateRange($startDate, $endDate)
    {
        $dates = [];
        while ($startDate->lte($endDate)) {
            $dates[] = $startDate->format('Y-m-d');
            $startDate->addDay();
        }
        return $dates;
    }

    public function clicks_data(Request $request)
    {
        $user = auth()->user();
        $currentMonth = Carbon::now()->format('Y-m');



        // Get all dates for the current and previous month
        if ($request->date) {
            $dates = explode(' - ', $request->date);

            // Convert to Carbon instances
           $startDate = Carbon::parse($dates[0])->startOfDay();
$endDate = Carbon::parse($dates[1])->endOfDay();


            // Generate date range for the selected period
            $currentMonthDates = $this->generateDateRange($startDate, $endDate);

            $startDate = Carbon::parse($dates[0])->startOfDay()->toDateString();
$endDate = Carbon::parse($dates[1])->endOfDay()->toDateString();


            // Calculate the previous period (one month before)
            $prevStartDate = Carbon::parse($dates[0])->startOfDay()->copy()->subMonth();
            $prevEndDate = Carbon::parse($dates[1])->endOfDay()->copy()->subMonth();


            // Generate date range for the previous period
            $previousMonthDates = $this->generateDateRange($prevStartDate, $prevEndDate);

            // Calculate the previous period (one month before)
            $prevStartDate = Carbon::parse($dates[0])->startOfDay()->copy()->subMonth()->toDateString();
            $prevEndDate = Carbon::parse($dates[1])->endOfDay()->copy()->subMonth()->toDateString();


            // Modify queries to use explicit date ranges
            $currentMonthSales = TrackingClick::selectRaw('DATE(date) as date, SUM(total_clicks) as total')
                ->whereBetween('date', [$startDate, $endDate])
                ->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');

            $previousMonthSales = TrackingClick::selectRaw('DATE(date) as date, SUM(total_clicks) as total')
                ->whereBetween('date', [$prevStartDate, $prevEndDate])
                ->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');

                  $previousMonth = Carbon::now()->subMonth()->format('Y-m');

        } else {
           $currentStart = Carbon::now()->startOfMonth();
$currentEnd = Carbon::now()->endOfMonth();
$previousStart = Carbon::now()->subMonth()->startOfMonth();
$previousEnd = Carbon::now()->subMonth()->endOfMonth();

$currentMonthDates = $this->generateDateRange($currentStart, $currentEnd);
$previousMonthDates = $this->generateDateRange($previousStart, $previousEnd);

// Current month clicks
$currentMonthSales = TrackingClick::selectRaw('DATE(date) as date, SUM(total_clicks) as total')
    ->whereBetween('date', [$currentStart, $currentEnd])
    ->where('publisher_id', $user->publisher_id)
    ->groupBy('date')
    ->orderBy('date')
    ->get()
    ->keyBy('date');

// Previous month clicks
$previousMonthSales = TrackingClick::selectRaw('DATE(date) as date, SUM(total_clicks) as total')
    ->whereBetween('date', [$previousStart, $previousEnd])
    ->where('publisher_id', $user->publisher_id)
    ->groupBy('date')
    ->orderBy('date')
    ->get()
    ->keyBy('date');



        }


        // Fill missing dates with 0
        $formattedCurrentMonthSales = [];
        foreach ($currentMonthDates as $date) {
            $formattedCurrentMonthSales[] = [
                'date' => $date,
                'total' => isset($currentMonthSales[$date]) ? round($currentMonthSales[$date]->total, 2) : 0
            ];
        }

        $formattedPreviousMonthSales = [];
        foreach ($previousMonthDates as $date) {
            $formattedPreviousMonthSales[] = [
                'date' => $date,
                'total' => isset($previousMonthSales[$date]) ? round($previousMonthSales[$date]->total, 2) : 0
            ];
        }

        return response()->json([
            'currentMonth' => $formattedCurrentMonthSales,
            'previousMonth' => $formattedPreviousMonthSales,
            'type' => $request->type,
            'color' => $request->color
        ]);
    }

    public function advertiserPerfomanceGraph(Request $request)
    {
        $user = auth()->user();

        if ($request->filled('date')) {
            $dates = explode(' - ', $request->date);
            $startDate = Carbon::parse($dates[0])->startOfDay();
            $endDate = Carbon::parse($dates[1])->endOfDay();

            $currentMonthDates = $this->generateDateRange($startDate, $endDate);

            // Correct previous month calculations
            $prevStartDate = Carbon::parse($dates[0])->startOfDay()->copy()->subMonth()->startOfDay();
            $prevEndDate = Carbon::parse($dates[1])->endOfDay()->copy()->subMonth()->endOfDay();

            $previousMonthDates = $this->generateDateRange($prevStartDate, $prevEndDate);
        } else {
            $currentMonth = Carbon::now()->startOfMonth();
            $previousMonth = Carbon::now()->subMonth()->startOfMonth();

            $currentMonthDates = $this->generateDateRange($currentMonth, Carbon::now()->endOfMonth());
            $previousMonthDates = $this->generateDateRange($previousMonth, Carbon::now()->subMonth()->endOfMonth());

            // Set default date ranges
            $startDate = $currentMonth;
            $endDate = Carbon::now()->endOfMonth();
            $prevStartDate = $previousMonth;
            $prevEndDate = Carbon::now()->subMonth()->endOfMonth();
        }

        // Determine field based on request type
        if ($request->type == 'commission') {
            $field = 'commission_amount';
            $type = 'Total Commission $';
        } elseif ($request->type == 'sales') {
            $field = 'received_sale_amount';
            $type = 'Total Sales $';
        } else {
            $field = null;
            $type = 'Total Transactions';
        }

        if ($field === null) {
            // Fetch actual sales/commission data for transactions count only
            $currentMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, COUNT(*) as total')
                ->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');

            $previousMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, COUNT(*) as total')

                ->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');
        } else {
            // Fetch sales/commission data with sum aggregation
            $currentMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(' . $field . ') as total')

                ->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');

            $previousMonthSales = Transaction::selectRaw('DATE(transaction_date) as date, SUM(' . $field . ') as total')

                ->where('publisher_id', $user->publisher_id)
                ->groupBy('date')
                ->orderBy('date')
                ->get()
                ->keyBy('date');
        }

        $formattedCurrentMonthSales = [];
        foreach ($currentMonthDates as $date) {
            $formattedCurrentMonthSales[] = [
                'date' => $date,
                'total' => isset($currentMonthSales[$date]) ? round($currentMonthSales[$date]->total, 2) : 0
            ];
        }

        $formattedPreviousMonthSales = [];
        foreach ($previousMonthDates as $date) {
            $formattedPreviousMonthSales[] = [
                'date' => $date,
                'total' => isset($previousMonthSales[$date]) ? round($previousMonthSales[$date]->total, 2) : 0
            ];
        }

        return response()->json([
            'currentMonth' => $formattedCurrentMonthSales,
            'previousMonth' => $formattedPreviousMonthSales,
            'startMonth' => $prevStartDate->toDateString(),
            'endMonth' => $prevEndDate->toDateString(),
            'type' => $type,
            'color' => $request->color
        ]);
    }


    public function overviewGraph(Request $request)
    {
        $status = $request->status;
    }
}
