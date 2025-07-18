<?php

namespace App\Traits\Provider\Advertiser;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\NetworkAdvertiserFetchStatusUpdateJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use App\Plugins\Awin\AwinTrait;

trait Awin
{
    use AwinTrait;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handleAwin($months)
    {
        $vars = $this->getAwinStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $notAvailable = $vars['not_available'];
        $limit = $vars['limit'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];
        $exceptionMsg = $vars['exception_msg'];

        Advertiser::where("source", $source)->update([
            "is_available" => $notAvailable
        ]);

        FetchDailyData::updateOrCreate([
            "path" => "AwinAdvertiserJob",
            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
            "queue" => $queue,
            "source" => $source,
            "type" => Vars::ADVERTISER
        ], [
            "name" => "Awin Advertiser Job",
            "payload" => json_encode([]),
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
            "sort" => $this->setSortingFetchDailyData($source)
        ]);

        $this->advertiserDataCompleteStatus($queue, $source);

    }

    private function getAwinStaticVar(): array
    {
        $source = Vars::AWIN;
        $name = strtoupper($source);
        $queue = Vars::AWIN_ON_QUEUE;
        $not_available = Vars::ADVERTISER_NOT_AVAILABLE;
        $limit = Vars::AWIN_ADVERTISER_LIMIT;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COMMAND",
            "queue_name" => $queue,
            "not_available" => $not_available,
            "limit" => $limit,
            "start_msg" => "FETCHING OF ADVERTISER AND ADVERTISER DETAIL INFORMATION HAS STARTED.",
            "end_msg" => "FETCHING OF ADVERTISER AND ADVERTISER DETAIL INFORMATION HAS BEEN COMPLETED.",
            "exception_msg" => "The data could not be fetched. Please check if the Awin credentials have been added correctly or if there is a server error. Please retry later.",
        ];
    }
}
