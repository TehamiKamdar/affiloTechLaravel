<?php

namespace App\Traits\Provider\Transaction;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Rakuten\TransactionJob;
use App\Models\FetchDailyData;
use App\Models\NetworkFetchData;
use App\Plugins\Rakuten\RakutenTrait;

trait Rakuten
{

    public function handleRakuten($months = 1)
    {
        $vars = $this->getRakutenStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $totalDays = $vars['total_days'];
        $subDays = $vars['sub_days'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

        $beginDays = $totalDays * $months;
        $endDays = $beginDays - $subDays;

//        Methods::customRakuten($module, $startMsg);

        $loop = true;
        while ($loop)
        {

            if($beginDays <= $subDays && $endDays <= 0)
                $loop = false;

            $begin = new \DateTime(now()->subDays($beginDays)->format("Y-m-d"));
            $end = new \DateTime(now()->subDays($endDays)->format("Y-m-d"));
            $begin = $begin->format("Y-m-d");
            $end = $end->format("Y-m-d");

//                Methods::customRakuten($module, "BEGIN DATE: " . $begin . " & END DATE: " . $end);

            $begin = "{$begin}%2012%3A00%3A00";
            $end = "{$end}%2011%3A59%3A59";

            FetchDailyData::updateOrCreate([
                "path" => "RakutenTransactionJob",
                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                "start_date" => $begin,
                "end_date" => $end,
                "queue" => $queue,
                "source" => $source,
                "type" => Vars::TRANSACTION
            ], [
                "name" => "Rakuten Transaction Job",
                "payload" => json_encode(['start' => $begin, 'end' => $end]),
                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);

//                TransactionJob::dispatch(['start' => $begin, 'end' => $end])->onQueue($queue);
//                $this->changeJobTime();

            $beginDays = $beginDays - $subDays;
            $endDays = $endDays - $subDays;

        }

        $this->transactionStatusUpdate($source, $months);

//        Methods::customRakuten($module, $endMsg);

    }

    private function getRakutenStaticVar(): array
    {
        $source = Vars::RAKUTEN;
        $name = strtoupper($source);
        $queue = Vars::RAKUTEN_TRANSACTION_ON_QUEUE;
        $total_days = Vars::LIMIT_30;
        $sub_days = Vars::LIMIT_10;

        return [
            "source" => $source,
            "module_name" => "{$name} TRANSACTION COMMAND",
            "queue_name" => $queue,
            "total_days" => $total_days,
            "sub_days" => $sub_days,
            "start_msg" => "FETCHING OF ADVERTISER TRANSACTION INFORMATION HAS STARTED.",
            "end_msg" => "FETCHING OF ADVERTISER TRANSACTION INFORMATION HAS BEEN COMPLETED.",
        ];
    }
}
