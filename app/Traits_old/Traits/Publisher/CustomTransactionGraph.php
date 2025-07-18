<?php

namespace App\Traits\Publisher;

use App\Helper\Static\Methods;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait CustomTransactionGraph
{

    public function getCustomTransactionsCount($toDate, $fromDate)
    {
        return Transaction::select('id')
            ->whereBetween('transaction_date', [$toDate, $fromDate])
            ->fetchPublisher(auth()->user())
            ->count();
    }

    public function setCustomPerformanceTransaction($toDate, $fromDate, $type)
    {
        $transactionCount = $this->getCustomTransactionsCount($toDate, $fromDate);
        $transaction = $this->getCustomCurrentDailyTransaction($toDate, $fromDate, $type);
        ksort($transaction);
        $transaction = array_values($transaction);

        $transactionAmount = array_filter($transaction);
        $minTransaction = count($transactionAmount) ? number_format(floatval(min($transactionAmount)), 2, '.', '') : 0.00;
        $maxTransaction = count($transactionAmount) ? number_format((floatval(max($transactionAmount)) + 20), 2, '.', '') : 0.00;

        return [
            "count" => Methods::numberFormatShort($transactionCount),
            "transaction" => $transaction,
            "min_value" => $minTransaction,
            "max_value" => $maxTransaction,
        ];
    }

    public function getCustomCurrentDailyTransaction($toDate, $fromDate, $type)
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

        $transactions = Transaction::select(DB::raw("COUNT(id) daily_transactions"), DB::raw("DATE_FORMAT(transaction_date, '{$queryDate}') as trans_date"));

        $transactions = $transactions
            ->whereBetween('transaction_date', [$toDate, $fromDate])
            ->fetchPublisher(auth()->user())
            ->groupBy('trans_date')
            ->orderBy('trans_date')
            ->get()
            ->pluck("daily_transactions", "trans_date")
            ->toArray();

        $begin = new \DateTime($toDate);
        $end = new \DateTime($fromDate);

        $interval = \DateInterval::createFromDateString('1 ' . ucwords($type));
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            if(isset($transactions[$dt->format($date)]))
            {
                $transactions[$dt->format($date)] = number_format($transactions[$dt->format($date)], 2);
            }
            else
            {
                $transactions[$dt->format($date)] = 0.00;
            }
        }

        return $transactions;

    }

    public function getCustomPreviousDailyTransaction()
    {
        $subMonth = Methods::subMonths();
        $transactions = Transaction::
            select(
                DB::raw("COUNT(id) daily_transactions"),
                DB::raw('DATE_FORMAT(transaction_date, "%d") as trans_date')
            )
            ->whereMonth('transaction_date', $subMonth)
            ->fetchPublisher(auth()->user())
            ->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%d-%m-%Y')"))
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
