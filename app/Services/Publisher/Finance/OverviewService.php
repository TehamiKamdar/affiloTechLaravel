<?php

namespace App\Services\Publisher\Finance;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AdvertiserPublisher;
use App\Models\Transaction;
use App\Models\TrackingClick;
use App\Models\Advertiser;
use App\Models\Website;
use Carbon\Carbon;


class OverviewService extends BaseService
{
    public function init(Request $request)
    {
        $title = "Overview";

        // Set the SEO title
        seo()->title(default: "{$title} — " . env("APP_NAME"));

        // Define headings
        $headings = [
            'Finance',
            $title
        ];

        $user = $request->user();

        if (auth()->user()->status != User::STATUS_ACTIVE || !auth()->user()->is_completed) {
            return $this->userInactiveMsg($user);
        } elseif (empty($user->active_website_id) || $user->active_website_status != Website::ACTIVE) {

            $this->websiteInactiveMsg($user);
            $startDate = now()->startOfYear()->toDateTimeString();
            $endDate = now()->endOfYear()->toDateTimeString();
            $data = [];
            $totalTransactions = 0;
            $totalSalesAmount = 0;
            $totalCommissionAmount = 0;
            $count = 0;

            return view("publisher.payments.overview", compact(
                'title',
                'headings',
                'data',
                'count',
                'startDate',
                'endDate',
                'totalTransactions',
                'totalSalesAmount',
                'totalCommissionAmount'
            ));
        }

        if ($request->filled('date')) {
            $date = $request->input("date");
            $dateRange = explode(" - ", $date);
            $startDate = Carbon::parse($dateRange[0])->format("Y-m-d 00:00:00");
            $endDate = Carbon::parse($dateRange[1])->format("Y-m-d 23:59:59");
        } else {
            $startDate = now()->startOfYear()->toDateTimeString();
            $endDate = now()->endOfYear()->toDateTimeString();
        }




        $data = AdvertiserPublisher::where('publisher_id', $user->publisher_id)
            ->join('advertisers', 'advertiser_publishers.advertiser_id', '=', 'advertisers.id')
            ->select('advertiser_publishers.advertiser_id', 'advertisers.name as advertiser_name', 'advertisers.logo', 'advertisers.fetch_logo_url', 'advertisers.sid')
            ->get()
            ->map(function ($advertiser) use ($user, $startDate, $endDate) {
                $adv = Advertiser::find($advertiser->advertiser_id);
                $transactions = Transaction::where('advertiser_id', $adv->advertiser_id)
                    ->where('publisher_id', $user->publisher_id)
                    ->whereBetween('transaction_date', [$startDate, $endDate]);

                $trackingClicks = TrackingClick::where('advertiser_id', $advertiser->advertiser_id)
                    ->where('publisher_id', $user->publisher_id)
                    ->whereBetween('created_at', [$startDate, $endDate]);

                return [
                    'advertiser_id' => $advertiser->advertiser_id,
                    'advertiser_name' => $advertiser->advertiser_name,
                    'advertiser_sid' => $advertiser->sid,
                    'logo' => $advertiser->logo,
                    'fetch_logo_url' => $advertiser->fetch_logo_url,
                    'total_transactions' => $transactions->count(),
                    'total_commissions_amount' => $transactions->sum('commission_amount'),
                    'total_received_sale_amount' => $transactions->sum('sale_amount'),
                    'total_clicks' => $trackingClicks->sum('total_clicks'),
                    'transactions' => $startDate,
                ];
            });

        // Handle AJAX request
        if ($request->ajax()) {
            return response()->json($data);
        }

        // Extract summary data
        $totalTransactions = $data->sum('total_transactions');
        $totalSalesAmount = $data->sum('total_received_sale_amount');
        $totalCommissionAmount = $data->sum('total_commissions_amount');
        $totalClicks = $data->sum('total_clicks');
        $count = $data->count();

        return view("publisher.payments.overview", compact(
            'title',
            'headings',
            'data',
            'count',
            'startDate',
            'endDate',
            'totalTransactions',
            'totalSalesAmount',
            'totalCommissionAmount',
            'totalClicks'
        ));
    }
}
