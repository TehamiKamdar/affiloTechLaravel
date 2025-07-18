<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\Advertiser;
use App\Models\PaymentHistory;
use Illuminate\Support\Facades\DB;

class   HomeController extends Controller
{
    public function index()
    {
        $total_sales = Transaction::sum('sale_amount');
        $total_pending = Transaction::where('commission_status', 'pending')->sum('before_percentage_commission');
        $total_approved = Transaction::where('commission_status', 'approved')->sum('before_percentage_commission');
        $total_declined = Transaction::where('commission_status', 'declined')->sum('before_percentage_commission');
        $total_commission = Transaction::sum('before_percentage_commission');
        $total_amount_to_pay = Transaction::where('paid_to_publisher', 1)->sum('commission_amount');
        $total_paid_amount = PaymentHistory::sum('amount');
        $latest_invoices = PaymentHistory::where('status', 'paid')->sum('amount');

        $topPublishers = DB::table('transactions')
            ->select(
                'publisher_id',
                DB::raw('ROUND(SUM(sale_amount), 2) as total_sales'),
                DB::raw('ROUND(SUM(before_percentage_commission), 2) as total_commission'),
                DB::raw('COUNT(*) as transaction_count')
            )->groupBy('publisher_id')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        $topAdvertisers = DB::table('transactions')
            ->select(
                'advertiser_id',
                DB::raw('ROUND(SUM(sale_amount), 2) as total_sales'),
                DB::raw('ROUND(SUM(before_percentage_commission), 2) as total_commission'),
                DB::raw('COUNT(*) as transaction_count')
            )->groupBy('advertiser_id')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        $totalAdvertiser = Advertiser::count();
        $totalnewAdvertiser = Advertiser::where('is_active', 1)->where('created_at', '>=', \Carbon\Carbon::now()->subDays(15))->count();
        $totalActive = Advertiser::where('is_active', 1)->count();
        $totalInActive = Advertiser::where('is_active', 0)->count();

        $advertisers = Advertiser::where('primary_regions', 'LIKE', '[%')->limit(10)->get();
        // return $advertisers;
        return view('admin.home', compact('total_sales', 'total_pending', 'total_approved', 'total_declined', 'total_commission', 'topPublishers', 'topAdvertisers', 'totalAdvertiser', 'totalnewAdvertiser', 'totalActive', 'totalInActive', 'advertisers', 'total_amount_to_pay', 'total_paid_amount', 'latest_invoices'));
    }

    public function getData(Request $request)
    {
       $year = $request->year ?? now()->year;
$month = $request->month ?? now()->month;

$data = Transaction::selectRaw('DAY(transaction_date) as day, SUM(sale_amount) as total')
    ->whereYear('transaction_date', $year)
    ->whereMonth('transaction_date', $month)
    ->groupBy('day')
    ->orderBy('day')
    ->get();

          

        // Build full list of days in the month
        $daysInMonth = now()->setYear($year)->setMonth($month - 1)->daysInMonth;
        $labels = [];
        $values = [];

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $labels[] = $i . ' ' . now()->format('M'); // e.g., 1 Jun, 2 Jun...
            $match = $data->firstWhere('day', $i);
            $values[] = $match ? $match->total : 0;
        }

        return response()->json([
            'labels' => $labels,
            'barData' => $values
        ]);
    }

    public function getTrackingLinkStats()
    {
        $baseQuery = \App\Models\GenerateLink::whereIn("path", [
            "GenerateTrackingLinkJob",
            "GenerateTrackingLinkWithSubIDJob"
        ])->whereMonth("created_at", now()->format("m"));

        $completed = (clone $baseQuery)->where("status", 0)->count();
        $pending = (clone $baseQuery)->where("status", 1)->count();
        $failed = (clone $baseQuery)->where("status", 1)->where("is_processing", 3)->count();

        return response()->json([
            'labels' => ['Completed', 'Pending', 'Failed'],
            'series' => [$completed, $pending, $failed]
        ]);
    }

    public function getDeepLinkStats()
    {
        $baseQuery = \App\Models\GenerateLink::whereIn("path", [
            "DeeplinkGenerateJob"
        ])->whereMonth("created_at", now()->format("m"));

        $completed = (clone $baseQuery)->where("status", 0)->count();
        $pending = (clone $baseQuery)->where("status", 1)->count();
        $failed = (clone $baseQuery)->where("status", 1)->where("is_processing", 3)->count();

        return response()->json([
            'labels' => ['Completed', 'Pending', 'Failed'],
            'series' => [$completed, $pending, $failed]
        ]);
    }

    public function getEmailApproveStats()
    {
        $baseQuery = \App\Models\EmailJob::whereIn("path", [
            "ApproveJob"
        ])->whereMonth("created_at", now()->format("m"));

        $completed = (clone $baseQuery)->where("status", 0)->count();
        $pending = (clone $baseQuery)->where("status", 1)->count();
        $failed = (clone $baseQuery)->where("status", 1)->where("is_processing", 3)->count();

        return response()->json([
            'labels' => ['Completed', 'Pending', 'Failed'],
            'series' => [$completed, $pending, $failed]
        ]);
    }
}
