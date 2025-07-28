<?php
namespace App\Services\Publisher\Reporting\Transaction;

use App\Models\TrackingClick;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Website;
use App\Services\Publisher\RootService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Opcodes\LogViewer\Facades\Cache;

class BaseService extends RootService
{
    protected function transactionLogic(Request $request)
    {
        $user = $request->user();

        // Check if active website is set and active
        if (empty($user->active_website_id) || !($user->active_website_status == Website::ACTIVE)) {
            $this->websiteInactiveMsg($user);
        }

        // Pagination variables
        $perPage = $request->get("per_page", 50);
        $page = $request->get("page", 1);

        // Transaction query and pagination
        if($request->path() == 'publisher/advertiser-performance'){
            $transactions = $this->transactionAdvertiserQuery($request, $user)
            ->paginate($perPage, ["*"], "page", $page);
        }else{
            $transactions = $this->transactionQuery($request, $user)
            ->paginate($perPage, ["*"], "page", $page);
        }


        // Calculate the from and to range for pagination
        $from = ($transactions->currentPage() - 1) * $transactions->perPage() + 1;
        $to = min($transactions->currentPage() * $transactions->perPage(), $transactions->total());

        // AJAX response for transaction list
         // Total statistics


        if ($request->filled("date")) {
        $date = $request->input("date");
        $dateRange = explode(" - ", $date);
        $startDate = Carbon::parse($dateRange[0])->format("Y-m-d 00:00:00");
        $endDate = Carbon::parse($dateRange[1])->format("Y-m-d 23:59:59");
        $totals = DB::table(DB::raw("transactions"))
            ->select(DB::raw("COUNT(id) as total_transactions, SUM(sale_amount) as total_sales_amount, SUM(commission_amount) as total_commission_amount"))
            ->where("transactions.publisher_id", $user->publisher_id)
            ->where("transactions.website_id", $user->active_website_id)->whereBetween("transactions.transaction_date", [$startDate, $endDate])
            ->first();

        }
        else
        {
        $totals = DB::table(DB::raw("transactions"))
            ->select(DB::raw("COUNT(id) as total_transactions, SUM(sale_amount) as total_sales_amount, SUM(commission_amount) as total_commission_amount"))
            ->where("transactions.publisher_id", $user->publisher_id)
            ->where("transactions.website_id", $user->active_website_id)
            ->first();
        }

        if ($request->ajax()) {
            return response()->json([
                "data" => view("publisher.reporting.ajax", compact("transactions"))->render(),
                "pagination" => (string) $transactions->links("partial.publisher_pagination"),
                "to" => $to,
                "from" => $from,
                 "total" => $transactions->total(),
            "total_transactions" => $totals->total_transactions,
            "total_sales_amount" => $totals->total_sales_amount,
            "total_commission_amount" => $totals->total_commission_amount
            ]);
        }





        // Transaction advertisers
        $transactionAdvertisers = Cache::remember("transaction_advertisers_" . $user->publisher_id . "_" . $user->active_website_id, 60, function () use ($user) {
            return DB::table(DB::raw("transactions"))
                     ->select("advertiser_name")
                     ->where("transactions.publisher_id", $user->publisher_id)
                     ->where("transactions.website_id", $user->active_website_id)
                     ->groupBy("advertiser_name")
                     ->get()
                     ->pluck("advertiser_name")
                     ->toArray();
        });

        // Transaction countries
        $transactionCountries = Cache::remember("transaction_countries_" . $user->publisher_id . "_" . $user->active_website_id, 60, function () use ($user) {
            return DB::table(DB::raw("transactions"))
                     ->select("advertiser_country")
                     ->where("transactions.publisher_id", $user->publisher_id)
                     ->where("transactions.website_id", $user->active_website_id)
                     ->groupBy("advertiser_country")
                     ->get()
                     ->pluck("advertiser_country")
                     ->toArray();
        });

        return collect([
            "transactions" => $transactions,
            "pagination" => (string) $transactions->links("partial.publisher_pagination"),
            "to" => $to,
            "from" => $from,
            "total" => $transactions->total(),
            "total_transactions" => $totals->total_transactions,
            "total_sales_amount" => $totals->total_sales_amount,
            "total_commission_amount" => $totals->total_commission_amount,
            "transaction_advertisers" => $transactionAdvertisers,
            "transaction_countries" => array_filter($transactionCountries)
        ]);
    }

    public function transactionAdvertiserQuery($request, $user){
        $query = Transaction::query()
        ->selectRaw("
            MAX(transactions.id) AS id,
            MAX(transactions.internal_advertiser_id) AS internal_advertiser_id,
            MAX(transactions.transaction_date) AS transaction_date,
            transactions.advertiser_name,
            MAX(transactions.transaction_id) AS transaction_id,
            SUM(transactions.sale_amount) AS sale_amount,
            SUM(transactions.commission_amount) AS commission_amount,
            MAX(transactions.commission_status) AS commission_status,
            MAX(transactions.sub_id) AS sub_id,
            MAX(transactions.advertiser_id) AS advertiser_id,
            MAX(transactions.payment_status) AS payment_status
        ")
        ->leftJoin(DB::raw('advertisers'), function ($join) {
            $join->on('advertisers.id', '=', 'transactions.internal_advertiser_id');
        })
        ->where('transactions.publisher_id', $user->publisher_id)
        ->where('transactions.website_id', $user->active_website_id);


        $this->applyFilters($query, $request);

        return $query->groupBy('transactions.advertiser_name');
    }

    public function transactionQuery(Request $request, User $user)
    {
        $query = Transaction::query()
    ->select([
        "transactions.id",
        "transactions.internal_advertiser_id",
        "transactions.transaction_date",
        "advertisers.name as advertiser_name", // Fetch advertiser name from advertisers table
        "transactions.transaction_id",
        "transactions.sale_amount",
        "transactions.commission_amount",
        "transactions.advertiser_id",
        "transactions.commission_status",
        "transactions.sub_id",
        "transactions.payment_status"
    ])
    ->leftJoin(DB::raw("advertisers"), function ($join) {
        $join->on("advertisers.id", "=", "transactions.internal_advertiser_id");
    })
    ->where("transactions.publisher_id", $user->publisher_id)
    ->where("transactions.website_id", $user->active_website_id);


        $this->applyFilters($query, $request);

        return $query->orderBy("transaction_date", "DESC");
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled("search")) {
            $search = $request->input("search");
            $query->where(function ($q) use ($search) {
                $q->where("transactions.transaction_id", "like", "%{$search}%")
                  ->orWhere("advertisers.name", "like", "%{$search}%")
                  ->orWhere("advertisers.sid", "like", "%{$search}%")
                  ->orWhere("advertisers.primary_regions", "like", "%{$search}%");
            });
        }

        if ($request->filled("status") && $request->status != "all") {
            $query->where("transactions.commission_status", $request->status);
        }

        if ($request->filled("date")) {
            $date = $request->input("date");
            $dateRange = explode(" - ", $date);
            $startDate = Carbon::parse($dateRange[0])->format("Y-m-d 00:00:00");
            $endDate = Carbon::parse($dateRange[1])->format("Y-m-d 23:59:59");
            $query->whereBetween("transactions.transaction_date", [$startDate, $endDate]);
        } else {
            $query->whereBetween("transactions.transaction_date", [now()->startOfYear(), now()]);
        }
    }

    public function ClickLogic( Request $request){
        $user = $request->user();

        // Check if active website is set and active
        if (empty($user->active_website_id) || !($user->active_website_status == Website::ACTIVE)) {
            $this->websiteInactiveMsg($user);
        }

        // Pagination variables
        $perPage = $request->get("per_page", 50);
        $page = $request->get("page", 1);


        $clicks = $this->clickQuery($request, $user)
        ->paginate($perPage, ["*"], "page", $page);

        $from = ($clicks->currentPage() - 1) * $clicks->perPage() + 1;
        $to = min($clicks->currentPage() * $clicks->perPage(), $clicks->total());

        // AJAX response for transaction list

        $totals = DB::table(DB::raw("tracking_clicks"))
                    ->select(DB::raw("SUM(total_clicks) as total_clicks"))
                    ->where("tracking_clicks.publisher_id", $user->publisher_id)
                    ->where("tracking_clicks.website_id", $user->active_website_id)
                    ->first();

        if ($request->ajax()) {
            return response()->json([
                "data" => view("publisher.reporting.clickajax", compact("clicks"))->render(),
                "pagination" => (string) $clicks->links("partial.publisher_pagination"),
                "to" => $to,
                "from" => $from,
                "total" => $clicks->total(),
                "total_clicks" => $totals->total_clicks,
            ]);
        }

        // Total statistics



        return collect([
            "clicks" =>$clicks,
            "pagination" => (string) $clicks->links("partial.publisher_pagination"),
            "to" => $to,
            "from" => $from,
            "total" => $clicks->total(),
            "total_clicks" => $totals->total_clicks,
        ]);
    }

    public function clickQuery($request, $user){
        $query = TrackingClick::query()
    ->selectRaw("
        tracking_clicks.advertiser_id,
        tracking_clicks.publisher_id,
        tracking_clicks.website_id,
        tracking_clicks.link_type,
        tracking_clicks.link_id,
        tracking_clicks.created_year,
        tracking_clicks.date,
        advertisers.name AS advertiser_name,
        advertisers.logo,
        advertisers.fetch_logo_url,
        advertisers.is_fetchable_logo,
        advertisers.sid,
        SUM(tracking_clicks.total_clicks) AS total_clicks
    ")
    ->leftJoin(DB::raw("advertisers"), function ($join) use ($user) {
        $join->on("advertisers.id", "=", "tracking_clicks.advertiser_id");
    })
    ->where("tracking_clicks.publisher_id", $user->publisher_id)
    ->where("tracking_clicks.website_id", $user->active_website_id);

$this->applyClickFilters($query, $request);

// Group only by advertiser_id and other non-aggregated fields
return $query->groupBy([
    'tracking_clicks.advertiser_id',
    'tracking_clicks.publisher_id',
    'tracking_clicks.website_id',
    'tracking_clicks.link_type',
    'tracking_clicks.link_id',
    'tracking_clicks.created_year',
    'tracking_clicks.date'
]);
    }

    private function applyClickFilters($query, Request $request)
    {
        if ($request->filled("search")) {
            $search = $request->input("search");
            $query->where(function ($q) use ($search) {
                $q->where("tracking_clicks.link_type", "like", "%{$search}%")
                  ->orWhere("advertisers.name", "like", "%{$search}%")
                  ->orWhere("advertisers.primary_regions", "like", "%{$search}%");
            });
        }

        if ($request->filled("date")) {
            $date = $request->input("date");
            $dateRange = explode(" - ", $date);
            $startDate = Carbon::parse($dateRange[0])->format("Y-m-d 00:00:00");
            $endDate = Carbon::parse($dateRange[1])->format("Y-m-d 23:59:59");
            $query->whereBetween("tracking_clicks.date", [$startDate, $endDate]);
        } else {
            $query->whereBetween("tracking_clicks.date", [now()->startOfYear(), now()]);
        }
    }
}
