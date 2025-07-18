<?php

namespace App\Traits\Provider\Transaction;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Admitad\TransactionJob;
use App\Models\FetchDailyData;
use Carbon\Carbon;
use App\Plugins\Admitad\AdmitadTrait;

trait Admitad
{
    use AdmitadTrait;

    public function handleAdmitad($months = 1)
    {
        $vars = $this->getAdmitadStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $totalDays = $vars['total_days'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];
        $offset = $vars['offset'];
        $limit = $vars['limit'];

        $beginDays = $totalDays * $months;
        $type = Vars::TRANSACTION;
        if($months == 1)
        {
            $type = Vars::TRANSACTION_SHORT;
            $beginDays = Vars::LIMIT_5;
        }

//        Methods::customAdmitad($module, $startMsg);

        $startDate = new \DateTime(now()->subDays($beginDays)->format("Y-m-d"));
        $startDate = Carbon::parse($startDate)->format("Y-m-d");
        $transactions = $this->sendAdmitadTransactionRequest(0, $startDate);

        if(isset($transactions["_meta"]['count']))
        {
            for ($job = 0; $job < ceil($transactions["_meta"]['count'] / $limit); $job++)
            {
                $offset = $job * $limit;
                FetchDailyData::updateOrCreate([
                    "path" => "AdmitadTransactionJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "start_date" => $startDate,
                    "offset" => $offset,
                    "queue" => $queue,
                    "source" => $source,
                    "type" => $type
                ], [
                    "name" => "Admitad Transaction Job",
                    "payload" => json_encode(["offset" => $offset, "start" => $startDate]),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    "sort" => $this->setSortingFetchDailyData($source),
                ]);
            }
        }

//        $loop = true;
//        while ($loop)
//        {
//
//            try {
//
//                if($offset <= $transactionCount)
//                    $loop = false;
//
//                TransactionJob::dispatch(['offset' => $offset,'start' => $startDate])->onQueue($queue);
//                $this->changeJobTime();
//
//                Methods::customAdmitad($module, "START DATE: " . $startDate . " & OFFSET: " . $offset);
//
//                $offset += $increaseLimit;
//
//            } catch (\Exception $exception) {
//                Methods::customError($module, $exception);
//            }
//
//        }

        $this->transactionStatusUpdate($source, $months);

//        Methods::customAdmitad($module, $endMsg);

    }

    private function getAdmitadStaticVar(): array
    {
        $source = Vars::ADMITAD;
        $name = strtoupper($source);
        $queue = Vars::ADMITAD_TRANSACTION_ON_QUEUE;
        $increaseLimit = Vars::ADMITAD_TRANSACTION_LIMIT;
        $total_days = Vars::LIMIT_30;
        $offset = Vars::OFFSET_0;

        return [
            "source" => $source,
            "module_name" => "{$name} TRANSACTION COMMAND",
            "queue_name" => $queue,
            "total_days" => $total_days,
            "offset" => $offset,
            "limit" => $increaseLimit,
            "start_msg" => "FETCHING OF ADVERTISER TRANSACTION INFORMATION HAS STARTED.",
            "end_msg" => "FETCHING OF ADVERTISER TRANSACTION INFORMATION HAS BEEN COMPLETED.",
        ];
    }

}
