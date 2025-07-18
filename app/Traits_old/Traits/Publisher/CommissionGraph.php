<?php

namespace App\Traits\Publisher;

use App\Helper\Static\Methods;
use App\Models\Role;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait CommissionGraph
{

    public function getCommissions($user)
    {

        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;

        return Cache::remember("getCommissions{$userID}{$websiteID}", 60 * 60, function () use($user) {
            $transactionsQuery = Transaction::selectRaw('SUM(commission_amount) as total_commission_amount, commission_status, commission_amount_currency');

            if($user->getAllowed())
            {
                $transactionsQuery->fetchPublisher(auth()->user());
            }

            return $transactionsQuery
                ->whereIn('commission_status', [Transaction::STATUS_PENDING, Transaction::STATUS_DECLINED, Transaction::STATUS_APPROVED])
                ->groupBy('commission_status')
                ->orderByDesc('total_commission_amount')
                ->get()
                ->toArray();
        });
    }

    public function getCommissionPercentage($user, $status = null)
    {
        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;

        return Cache::remember("getCommissionPercentage{$userID}{$websiteID}{$status}", 60 * 60, function () use($user, $status) {

            $currentMonthCommissionQuery = Transaction::whereMonth('transaction_date', date('m'))->whereYear('transaction_date', date('Y'))
                ->select('commission_amount');

            $previousMonthCommissionQuery = Transaction::whereMonth('transaction_date', date('m', strtotime('-1 month')))
                ->select('commission_amount');

            if($user->getAllowed())
            {
                $currentMonthCommissionQuery->fetchPublisher($user);
                $previousMonthCommissionQuery->fetchPublisher($user);
            }

            if ($status) {
                $currentMonthCommissionQuery->where('commission_status', $status);
                $previousMonthCommissionQuery->where('commission_status', $status);
            }

            $currentMonthCommission = $currentMonthCommissionQuery->sum('commission_amount');
            $previousMonthCommission = $previousMonthCommissionQuery->sum('commission_amount');

            return Methods::returnPerGrowth($previousMonthCommission, $currentMonthCommission);

        });

    }

    public function setPerformanceCommission($user)
    {
        $totalCommission = $this->getTotalCommission($user);
        $dailyCurrentCommission = $this->getCurrentDailyCommission($user);
        $dailyCurrentCommission = array_values($dailyCurrentCommission);
        $dailyPreviousCommission = $this->getPreviousDailyCommission($user);
        $dailyPreviousCommission = array_values($dailyPreviousCommission);

        $getMinMaxCommission = array_filter(array_merge($dailyPreviousCommission, $dailyCurrentCommission));
        $minCommission = $getMinMaxCommission ? min($getMinMaxCommission) : 1;
        $maxCommission = $getMinMaxCommission ? floatval(max($getMinMaxCommission)) + 20 : 20;
        $commissionPercentage = $this->getCommissionPercentage($user);

        return [
            "count" => Methods::numberFormatShort($totalCommission),
            "min_value" => $minCommission,
            "max_value" => $maxCommission,
            "dailyCurrentMonth" => $dailyCurrentCommission,
            "dailyPreviousMonth" => $dailyPreviousCommission,
            ...$commissionPercentage
        ];
    }

    public function getTotalCommission($user)
    {
        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;

        return Cache::remember("getTotalCommission{$userID}{$websiteID}", 60 * 60, function () use($user) {
            $transactions = Transaction::query();
            if($user->getAllowed())
                $transactions->fetchPublisher(auth()->user());
            return $transactions->sum("commission_amount");
        });
    }

    public function getCurrentDailyCommission($user)
    {
        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;

        return Cache::remember("getCurrentDailyCommission{$userID}{$websiteID}", 60 * 60, function () use($user) {
            $transactionsQuery = Transaction::query()
                ->selectRaw("SUM(commission_amount) as daily_commissions")
                ->selectRaw('DATE_FORMAT(transaction_date, "%d") as trans_date')
                ->whereYear('transaction_date', date('Y'))
                ->whereMonth('transaction_date', date('m'));

            if($user->getAllowed())
                $transactionsQuery->fetchPublisher(auth()->user());

            $transactions = $transactionsQuery
                ->groupByRaw("DATE_FORMAT(transaction_date, '%d-%m-%Y')")
                ->orderBy('transaction_date')
                ->get()
                ->pluck("daily_commissions", "trans_date")
                ->toArray();

            $begin = new \DateTime(now()->format("Y-m-01 00:00:00"));
            $end = new \DateTime(now()->format("Y-m-t 23:59:59"));

            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                $day = $dt->format("d");
                $transactions[$day] = number_format($transactions[$day] ?? 0, 2, '.', '');
            }

            ksort($transactions);

            return $transactions;
        });

    }

    public function getPreviousDailyCommission($user)
    {
        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;

        return Cache::remember("getPreviousDailyCommission{$userID}{$websiteID}", 60 * 60, function () use($user) {

            $transactions = Transaction::
            select(
                DB::raw("SUM(commission_amount) daily_commissions"),
                DB::raw('DATE_FORMAT(transaction_date, "%d") as trans_date')
            );

            $user = auth()->user();
            if($user->getAllowed())
                $transactions = $transactions->fetchPublisher(auth()->user());

            $subMonth = Methods::subMonths();

            $transactions = $transactions->whereMonth('transaction_date', $subMonth)
                ->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%d-%m-%Y')"))
                ->orderBy('transaction_date')
                ->get()
                ->pluck("daily_commissions", "trans_date")->toArray();

            $begin = new \DateTime($subMonth->format("Y-m-01 00:00:00"));
            $end = new \DateTime($subMonth->format("Y-m-t 23:59:59"));

            $interval = \DateInterval::createFromDateString('1 day');
            $period = new \DatePeriod($begin, $interval, $end);

            foreach ($period as $dt) {
                if(isset($transactions[$dt->format("d")]))
                {
                    $transactions[$dt->format("d")] = number_format($transactions[$dt->format("d")], 2, '.', '');
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
