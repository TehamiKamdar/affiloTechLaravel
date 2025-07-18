<?php

namespace App\Traits\Publisher;

use App\Helper\Static\Methods;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

trait CustomCommissionGraph
{
    public function getCustomCommissionPercentage($toDate, $fromDate, $type, $status = null)
    {
        $currentMonthCommission = Transaction::select('commission_amount')
            ->fetchPublisher(auth()->user())
            ->whereBetween('transaction_date', [$toDate, $fromDate]);

        if($status)
            $currentMonthCommission = $currentMonthCommission->where('commission_status', $status);

        $currentMonthCommission = $currentMonthCommission->sum('commission_amount');

        $previousMonthCommission = Transaction::select('commission_amount')
            ->fetchPublisher(auth()->user())
            ->whereMonth('transaction_date', date('m', strtotime('-1 month')));

        if($status)
            $previousMonthCommission = $previousMonthCommission->where('commission_status', $status);

        $previousMonthCommission = $previousMonthCommission->sum('commission_amount');

        return Methods::returnPerGrowth($previousMonthCommission, $currentMonthCommission);

    }

    public function setCustomPerformanceCommission($toDate, $fromDate, $type)
    {
        $totalCommission = $this->getCustomTotalCommission($toDate, $fromDate);
        $commission = $this->getCustomCommission($toDate, $fromDate, $type);
        ksort($commission);
        $commission = array_values($commission);

        $commission = array_map(function ($c) {
            return number_format($c, 2, '.', '');
        }, $commission);

        $minCommission = array_filter($commission);
        $minCommission = $minCommission ? min($minCommission) : 1;
        $maxCommission = $commission ? floatval(max($commission)) + 20 : 0;

        return [
            "count" => Methods::numberFormatShort($totalCommission),
            "commission" => $commission,
            "min_value" => $minCommission,
            "max_value" => $maxCommission,
        ];
    }

    public function getCustomTotalCommission($toDate, $fromDate)
    {
        return Transaction::fetchPublisher(auth()->user())
            ->whereBetween('transaction_date', [$toDate, $fromDate])
            ->sum("commission_amount");
    }

    public function getCustomCommission($toDate, $fromDate, $type)
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

        $transactions = Transaction::select(DB::raw("SUM(commission_amount) daily_commissions"), DB::raw("DATE_FORMAT(transaction_date, '{$queryDate}') as trans_date"));

        $transactions = $transactions
            ->whereBetween('transaction_date', [$toDate, $fromDate])
            ->fetchPublisher(auth()->user())
            ->groupBy('trans_date')
            ->orderBy('trans_date')
            ->get()
            ->pluck("daily_commissions", "trans_date")
            ->toArray();

        $begin = new \DateTime($toDate);
        $end = new \DateTime($fromDate);

        $interval = \DateInterval::createFromDateString('1 ' . ucwords($type));
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            if(isset($transactions[$dt->format($date)]))
            {
                $transactions[$dt->format($date)] = number_format($transactions[$dt->format($date)], 2, '.', '');
            }
            else
            {
                $transactions[$dt->format($date)] = 0.00;
            }
        }

        return $transactions;

    }

}
