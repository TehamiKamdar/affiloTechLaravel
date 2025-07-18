<?php

namespace App\Traits\Publisher;

use App\Helper\Static\Methods;
use App\Models\Role;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait SalesGraph
{

    public function getTopFiveSales($user)
    {
        return Cache::remember("getTopFiveSales{$user->id}", 60 * 60, function () use($user) {
            $transactions = Transaction::selectRaw('sum(sale_amount) as total_sales_amount, advertiser_name, sale_amount_currency, external_advertiser_id');

            if($user->getAllowed())
                $transactions = $transactions->fetchPublisher($user);

            return $transactions
                ->whereBetween("transaction_date", [Carbon::now()->subMonths(3)->format("Y-m-01 00:00:00"), Carbon::now()->format("Y-m-t 23:59:59")])
                ->groupBy('advertiser_name')
                ->orderBy('total_sales_amount', 'DESC')
                ->take(5)
                ->get();
        });
    }

    public function getSalePercentage($user)
    {
        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;
        return Cache::remember("getSalePercentage{$userID}{$websiteID}", 60 * 60, function () use($user) {

            $currentMonthTransaction = Transaction::select('sale_amount');

            if($user->getAllowed())
                $currentMonthTransaction = $currentMonthTransaction->fetchPublisher($user);

            $currentMonthTransaction = $currentMonthTransaction->whereMonth('transaction_date', date('m'))->whereYear('transaction_date', date('Y'))->count();

            $previousMonthTransaction = Transaction::select('sale_amount');

            if($user->getAllowed())
                $previousMonthTransaction = $previousMonthTransaction->fetchPublisher($user);

            $previousMonthTransaction = $previousMonthTransaction->whereMonth('transaction_date', date('m', strtotime('-1 month')))->count();

            return Methods::returnPerGrowth($previousMonthTransaction, $currentMonthTransaction);

        });
    }

    public function getSalesCount($user)
    {
        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;
        return Cache::remember("getSalesCount{$userID}{$websiteID}", 60 * 60, function () use($user) {
            $transaction = Transaction::select('sale_amount');

            if($user->getAllowed())
                $transaction = $transaction->fetchPublisher($user);

            return $transaction->sum('sale_amount');
        });
    }

    public function setPerformanceSale($user)
    {
        $saleCount = $this->getSalesCount($user);
        $dailyCurrentSale = $this->getCurrentDailySale($user);
        $dailyCurrentSale = array_values($dailyCurrentSale);
        $dailyPreviousSale = $this->getPreviousDailySale($user);
        $dailyPreviousSale = array_values($dailyPreviousSale);

        $getMinMaxSale = array_filter(array_merge($dailyCurrentSale, $dailyPreviousSale));
        $minSale = $getMinMaxSale ? floatval(min($getMinMaxSale)) : 1;
        $maxSale = $getMinMaxSale ? floatval(max($getMinMaxSale)) + 20 : 20;
        $salePercentage = $this->getSalePercentage($user);

        return [
            "count" => Methods::numberFormatShort($saleCount),
            "min_value" => $minSale,
            "max_value" => $maxSale,
            "dailyCurrentMonth" => $dailyCurrentSale,
            "dailyPreviousMonth" => $dailyPreviousSale,
            ...$salePercentage
        ];
    }

    public function getCurrentDailySale($user)
    {
        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;
        return Cache::remember("getCurrentDailySale{$userID}{$websiteID}", 60 * 60, function () use($user) {
            $transactions = Transaction::
            select(
                DB::raw("SUM(sale_amount) daily_sale_amount"),
                DB::raw('DATE_FORMAT(transaction_date, "%d") as trans_date')
            )->whereYear('transaction_date', date('Y'))
                ->whereMonth('transaction_date', date('m'));

            if($user->getAllowed())
                $transactions = $transactions->fetchPublisher($user);

            $transactions = $transactions->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%d-%m-%Y')"))
                ->orderBy('transaction_date')
                ->get()
                ->pluck("daily_sale_amount", "trans_date")->toArray();

            $begin = new \DateTime(now()->format("Y-m-01 00:00:00"));
            $end = new \DateTime(now()->format("Y-m-t 23:59:59"));

            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                if(isset($transactions[$dt->format("d")]))
                {
                    $number = number_format($transactions[$dt->format("d")], 2);
                    $number = str_replace(',', '', $number);
                    $transactions[$dt->format("d")] = $number;
                }
                else
                {
                    $transactions[$dt->format("d")] = 0.00;
                }
            }

            ksort($transactions);

            return $transactions;
        });

    }

    public function getPreviousDailySale($user)
    {
        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;
        return Cache::remember("getPreviousDailySale{$userID}{$websiteID}", 60 * 60, function () use($user) {
            $subMonth = Methods::subMonths();
            $transactions = Transaction::
            select(
                DB::raw("SUM(sale_amount) daily_sale_amount"),
                DB::raw('DATE_FORMAT(transaction_date, "%d") as trans_date')
            )
                ->whereMonth('transaction_date', $subMonth);

            if($user->getAllowed())
                $transactions = $transactions->fetchPublisher($user);

            $transactions = $transactions->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%d-%m-%Y')"))
                ->orderBy('transaction_date')
                ->get()
                ->pluck("daily_sale_amount", "trans_date")->toArray();

            $begin = new \DateTime($subMonth->format("Y-m-01 00:00:00"));
            $end = new \DateTime($subMonth->format("Y-m-t 23:59:59"));

            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                if(isset($transactions[$dt->format("d")]))
                {
                    $transactions[$dt->format("d")] = number_format($transactions[$dt->format("d")], 2);
                }
                else
                {
                    $transactions[$dt->format("d")] = 0.00;
                }
            }

            ksort($transactions);

            return $transactions;
        });
    }

}
