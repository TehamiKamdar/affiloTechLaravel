<?php

namespace App\Traits\Publisher;

use App\Helper\Static\Methods;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait CustomSalesGraph
{

    public function getCustomTopFiveSales()
    {
        return Transaction::selectRaw('sum(sale_amount) as total_sales_amount, advertiser_name, sale_amount_currency, external_advertiser_id')
            ->fetchPublisher(auth()->user())
            ->whereBetween("transaction_date", [Carbon::now()->subMonths(3)->format("Y-m-01 00:00:00"), Carbon::now()->format("Y-m-t 23:59:59")])
            ->groupBy('advertiser_name')
            ->orderBy('total_sales_amount', 'DESC')
            ->take(5)
            ->get();
    }

    public function getCustomSalePercentage()
    {
        $currentMonthTransaction = Transaction::select('sale_amount')
            ->fetchPublisher(auth()->user())
            ->whereMonth('transaction_date', date('m'))
            ->whereYear('transaction_date', date('Y'))
            ->count();

        $previousMonthTransaction = Transaction::select('sale_amount')
            ->fetchPublisher(auth()->user())
            ->whereMonth('transaction_date', date('m', strtotime('-1 month')))
            ->count();

        return Methods::returnPerGrowth($previousMonthTransaction, $currentMonthTransaction);
    }

    public function getCustomSalesCount($toDate, $fromDate)
    {
        return Transaction::select('sale_amount')
            ->whereBetween('transaction_date', [$toDate, $fromDate])
            ->fetchPublisher(auth()->user())
            ->sum('sale_amount');
    }

    public function setCustomPerformanceSale($toDate, $fromDate, $type)
    {
        $saleCount = $this->getCustomSalesCount($toDate, $fromDate);
        $sale = $this->getCustomCurrentDailySale($toDate, $fromDate, $type);
        ksort($sale);
        $sale = array_values($sale);

        $sale = array_map(function ($s) {
            return number_format($s, 2, '.', '');
        }, $sale);

        $minSale = array_filter($sale);
        $minSale = min($minSale);
        $maxSale = floatval(max($sale)) + 20;

        return [
            "count" => Methods::numberFormatShort($saleCount),
            "sale" => $sale,
            "min_value" => $minSale,
            "max_value" => $maxSale
        ];
    }

    public function getCustomCurrentDailySale($toDate, $fromDate, $type)
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

        $transactions = Transaction::select(DB::raw("SUM(sale_amount) daily_sale_amount"), DB::raw("DATE_FORMAT(transaction_date, '{$queryDate}') as trans_date"));

        $transactions = $transactions
            ->whereBetween('transaction_date', [$toDate, $fromDate])
            ->fetchPublisher(auth()->user())
            ->groupBy('trans_date')
            ->orderBy('trans_date')
            ->get()
            ->pluck("daily_sale_amount", "trans_date")
            ->toArray();

        $begin = new \DateTime($toDate);
        $end = new \DateTime($fromDate);

        $interval = \DateInterval::createFromDateString('1 ' . ucwords($type));
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            if(isset($transactions[$dt->format($date)]))
            {
                $transactions[$dt->format($date)] = $transactions[$dt->format($date)];
            }
            else
            {
                $transactions[$dt->format($date)] = 0.00;
            }
        }

        return $transactions;

    }

    public function getCustomPreviousDailySale()
    {
        $subMonth = Methods::subMonths();
        $transactions = Transaction::
            select(
                DB::raw("SUM(sale_amount) daily_sale_amount"),
                DB::raw('DATE_FORMAT(transaction_date, "%d") as trans_date')
            )
            ->whereMonth('transaction_date', $subMonth)
            ->fetchPublisher(auth()->user())
            ->groupBy(DB::raw("DATE_FORMAT(transaction_date, '%d-%m-%Y')"))
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

    }

}
