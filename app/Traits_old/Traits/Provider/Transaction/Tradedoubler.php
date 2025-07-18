<?php

namespace App\Traits\Provider\Transaction;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Tradedoubler\TransactionJob;
use App\Models\FetchDailyData;
use Plugins\Traderdoubler\TradedoublerTrait;

trait Tradedoubler
{
    use TradedoublerTrait;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handleTradedoubler($months = 1)
    {
        $vars = $this->getTradedoublerStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $totalDays = $vars['total_days'];
        $subDays = $vars['sub_days'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

        $beginDays = $totalDays * $months;
        $endDays = $beginDays - $subDays;

//        Methods::customTradedoubler($module, $startMsg);

        $loop = true;
        while ($loop)
        {

            if($beginDays <= $subDays && $endDays <= 0)
                $loop = false;

            $begin = new \DateTime(now()->subDays($beginDays)->format("Y-m-d"));
            $end = new \DateTime(now()->subDays($endDays)->format("Y-m-d"));

            FetchDailyData::updateOrCreate([
                "path" => "TradedoublerTransactionJob",
                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                "start_date" => $begin->format("Ymd"),
                "end_date" => $end->format("Ymd"),
                "queue" => $queue,
                "source" => $source,
                "type" => Vars::TRANSACTION
            ], [
                "name" => "Tradedoubler Transaction Job",
                "payload" => json_encode(['start' => $begin->format("Ymd"), 'end' => $end->format("Ymd")]),
                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);

//                TransactionJob::dispatch(['start' => $begin->format("Ymd"), 'end' => $end->format("Ymd")])->onQueue($queue);
//                $this->changeJobTime();

//                Methods::customTradedoubler($module, "BEGIN DATE: " . $begin->format("Ymd") . " & END DATE: " . $end->format("Ymd"));;

            $beginDays = $beginDays - $subDays;
            $endDays = $endDays - $subDays;

        }

        $this->transactionStatusUpdate($source, $months);

//        Methods::customTradedoubler($module, $endMsg);
    }

    private function getTradedoublerStaticVar(): array
    {
        $source = Vars::TRADEDOUBLER;
        $name = strtoupper($source);
        $queue = Vars::TRADEDOUBLER_TRANSACTION_ON_QUEUE;
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
