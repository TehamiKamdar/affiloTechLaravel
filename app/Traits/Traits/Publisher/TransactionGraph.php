<?php

namespace App\Traits\Publisher;

use App\Helper\Static\Methods;
use App\Models\Role;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait TransactionGraph
{

    public function getTransactionsCount($user)
    {
        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;

        return Cache::remember("getTransactionsCount{$userID}{$websiteID}", 60 * 60, function () use($user) {
            $transactions = Transaction::select('id');
            if($user->getAllowed())
                $transactions = $transactions->fetchPublisher($user);
            return $transactions->count();
        });
    }

    public function getTransactionPercentage($user)
    {
        $userID = $user->id;
        $websiteID = $user->active_website_id ?? null;
        return Cache::remember("getTransactionPercentage{$userID}{$websiteID}", 60 * 60, function () use($user) {
            $currentMonthTransaction = Transaction::select('id');
            if($user->getAllowed())
                $currentMonthTransaction = $currentMonthTransaction->fetchPublisher($user);
            $currentMonthTransaction = $currentMonthTransaction->whereMonth('transaction_date', date('m'))->whereYear('transaction_date', date('Y'))->count();

            $previousMonthTransaction = Transaction::select('id');
            if($user->getAllowed())
                $previousMonthTransaction = $previousMonthTransaction->fetchPublisher($user);
            $previousMonthTransaction = $previousMonthTransaction->whereMonth('transaction_date', date('m', strtotime('-1 month')))->count();

            return Methods::returnPerGrowth($previousMonthTransaction, $currentMonthTransaction);
        });
    }

    public function setPerformanceTransaction($user)
    {
        $transactionCount = $this->getTransactionsCount($user);
        $dailyCurrentTransaction = $this->getCurrentDailyTransaction($user);
        $dailyCurrentTransaction = array_values($dailyCurrentTransaction);
        $dailyPreviousTransaction = $this->getPreviousDailyTransaction($user);
        $dailyPreviousTransaction = array_values($dailyPreviousTransaction);

        $getMinMaxTransaction = array_filter(array_merge($dailyCurrentTransaction, $dailyPreviousTransaction));
        $minTransaction = $getMinMaxTransaction ? min($getMinMaxTransaction) : 1;
        $maxTransaction = $getMinMaxTransaction ? max($getMinMaxTransaction) + 20 : 20;
        $transactionPercentage = $this->getTransactionPercentage($user);

        return [
            "count" => Methods::numberFormatShort($transactionCount),
            "min_value" => $minTransaction,
            "max_value" => $maxTransaction,
            "dailyCurrentMonth" => $dailyCurrentTransaction,
            "dailyPreviousMonth" => $dailyPreviousTransaction,
            ...$transactionPercentage
        ];
    }

    public function getCurrentDailyTransaction()
    {
        $transactions = Transaction::
            select(
                DB::raw("COUNT(id) daily_transactions"),
                DB::raw('DATE_FORMAT(transaction_date, "%d") as trans_date')
            )->whereYear('transaction_date', date('Y'))
            ->whereMonth('transaction_date', date('m'));

        $user = auth()->user();
        if($user->getAllowed())
            $transactions = $transactions->fetchPublisher($user);

        $transactions = $transactions->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%d-%m-%Y')"))
            ->orderBy('transaction_date')
            ->get()
            ->pluck("daily_transactions", "trans_date")->toArray();

        $begin = new \DateTime(now()->format("Y-m-01 00:00:00"));
        $end = new \DateTime(now()->format("Y-m-t 23:59:59"));

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            if(!isset($transactions[$dt->format("d")]))
            {
                $transactions[$dt->format("d")] = 0.00;
            }
        }

        ksort($transactions);

        return $transactions;

    }

    public function getPreviousDailyTransaction()
    {
        $subMonth = Methods::subMonths();
        $transactions = Transaction::
            select(
                DB::raw("COUNT(id) daily_transactions"),
                DB::raw('DATE_FORMAT(transaction_date, "%d") as trans_date')
            )
            ->whereMonth('transaction_date', $subMonth);

        $user = auth()->user();
        if($user->getAllowed())
            $transactions = $transactions->fetchPublisher($user);

        $transactions = $transactions->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%d-%m-%Y')"))
            ->orderBy('transaction_date')
            ->get()
            ->pluck("daily_transactions", "trans_date")->toArray();

        $begin = new \DateTime($subMonth->format("Y-m-01 00:00:00"));
        $end = new \DateTime($subMonth->format("Y-m-t 23:59:59"));

        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            if(!isset($transactions[$dt->format("d")]))
            {
                $transactions[$dt->format("d")] = 0.00;
            }
        }

        ksort($transactions);

        return $transactions;

    }

}
