<?php

namespace App\Traits\Provider\Transaction;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Awin\TransactionJob;
use App\Models\FetchDailyData;
use App\Models\NetworkFetchData;
use Carbon\Carbon;
use Plugins\Awin\AwinTrait;

trait Awin
{

    public function handleAwin($months = 1)
    {
        $vars = $this->getAwinStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $totalDays = $vars['total_days'];
        $subDays = $vars['sub_days'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

        $beginDays = $totalDays * $months;
        $endDays = $beginDays - $subDays;

//        Methods::customAwin($module, $startMsg);

        $type = Vars::TRANSACTION;
        if($months == 1)
        {
            $type = Vars::TRANSACTION_SHORT;
            $beginDays = Vars::LIMIT_5;
            $begin = new \DateTime(now()->subDays($beginDays)->format("Y-m-d"));
            $end = new \DateTime(now()->format("Y-m-d"));

            FetchDailyData::updateOrCreate([
                "path" => "AwinTransactionJob",
                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                "start_date" => $begin->format("Y-m-d"),
                "end_date" => $end->format("Y-m-d"),
                "queue" => $queue,
                "source" => $source,
                "type" => $type
            ], [
                "name" => "Awin Transaction Job",
                "payload" => json_encode(['start' => $begin->format("Y-m-d"), 'end' => $end->format("Y-m-d")]),
                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);
        }
        else
        {
            $loop = true;
            while ($loop)
            {

                if($beginDays <= $subDays && $endDays <= 0)
                    $loop = false;

                $begin = new \DateTime(now()->subDays($beginDays)->format("Y-m-d"));
                $end = new \DateTime(now()->subDays($endDays)->format("Y-m-d"));

                FetchDailyData::updateOrCreate([
                    "path" => "AwinTransactionJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "start_date" => $begin->format("Y-m-d"),
                    "end_date" => $end->format("Y-m-d"),
                    "queue" => $queue,
                    "source" => $source,
                    "type" => $type
                ], [
                    "name" => "Awin Transaction Job",
                    "payload" => json_encode(['start' => $begin->format("Y-m-d"), 'end' => $end->format("Y-m-d")]),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    "sort" => $this->setSortingFetchDailyData($source),
                ]);

//                TransactionJob::dispatch(['start' => $begin->format("Y-m-d"), 'end' => $end->format("Y-m-d")])->onQueue($queue);

//                Methods::customAwin($module, "BEGIN DATE: " . $begin->format("Y-m-d") . " & END DATE: " . $end->format("Y-m-d"));;

                $beginDays = $beginDays - $subDays;
                $endDays = $endDays - $subDays;

            }
        }

        $this->transactionStatusUpdate($source, $months);

//        Methods::customAwin($module, $endMsg);

    }

    private function getAwinStaticVar(): array
    {
        $source = Vars::AWIN;
        $name = strtoupper($source);
        $queue = Vars::AWIN_TRANSACTION_ON_QUEUE;
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
