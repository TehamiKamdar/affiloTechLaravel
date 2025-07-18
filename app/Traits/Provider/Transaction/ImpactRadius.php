<?php

namespace App\Traits\Provider\Transaction;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\ImpactRadius\TransactionJob;
use App\Models\FetchDailyData;
use App\Models\NetworkFetchData;
use Carbon\Carbon;
use App\Plugins\ImpactRadius\ImpactRadiusTrait;

trait ImpactRadius
{

    public function handleImpactRadius($months = 1)
    {
        $vars = $this->getImpactStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $totalDays = $vars['total_days'];
        $subDays = $vars['sub_days'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

        $beginDays = $totalDays * $months;
        $endDays = $beginDays - $subDays;
        if($months == 1) {
            $beginDays = Vars::LIMIT_5;
            $begin = new \DateTime(now()->subDays($beginDays)->format("Y-m-d"));
            $end = new \DateTime(now()->subDays($endDays)->format("Y-m-d"));

            FetchDailyData::updateOrCreate([
                "path" => "ImpactTransactionJob",
                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                "start_date" => $begin->format("Y-m-d 00:00:00"),
                "end_date" => $end->format("Y-m-d 23:59:59"),
                "queue" => $queue,
                "source" => $source,
                "type" => Vars::TRANSACTION_SHORT
            ], [
                "name" => "Impact Transaction Job",
                "payload" => json_encode(['start' => $begin->format("Y-m-d\\T\\00:00:00\\Z"), 'end' => $end->format("Y-m-d\\T\\23:59:59\\Z")]),
                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                "sort" => $this->setSortingFetchDailyData($source),
            ]);
        }
        else {

            $loop = true;
            while ($loop)
            {

                if($beginDays <= $subDays && $endDays <= 0)
                    $loop = false;

                $begin = new \DateTime(now()->subDays($beginDays)->format("Y-m-d"));
                $end = new \DateTime(now()->subDays($endDays)->format("Y-m-d"));

//                Methods::customImpactRadius($module, "BEGIN DATE: " . $begin->format("Y-m-d") . " & END DATE: " . $end->format("Y-m-d"));;

                FetchDailyData::updateOrCreate([
                    "path" => "ImpactTransactionJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "start_date" => $begin->format("Y-m-d 00:00:00"),
                    "end_date" => $end->format("Y-m-d 23:59:59"),
                    "queue" => $queue,
                    "source" => $source,
                    "type" => Vars::TRANSACTION
                ], [
                    "name" => "Impact Transaction Job",
                    "payload" => json_encode(['start' => $begin->format("Y-m-d\\T\\00:00:00\\Z"), 'end' => $end->format("Y-m-d\\T\\23:59:59\\Z")]),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    "sort" => $this->setSortingFetchDailyData($source),
                ]);

//                TransactionJob::dispatch(['start' => $begin->format("Y-m-d\\T\\00:00:00\\Z"), 'end' => $end->format("Y-m-d\\T\\23:59:59\\Z")])->onQueue($queue);
//                $this->changeJobTime();

                $beginDays = $beginDays - $subDays;
                $endDays = $endDays - $subDays;

            }

        }

//        Methods::customImpactRadius($module, $startMsg);

        $this->transactionStatusUpdate($source, $months);

//        Methods::customImpactRadius($module, $endMsg);

    }

    private function getImpactStaticVar(): array
    {
        $source = Vars::IMPACT_RADIUS;
        $name = strtoupper($source);
        $queue = Vars::IMPACT_RADIUS_TRANSACTION_ON_QUEUE;
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
