<?php

namespace App\Traits\Provider\Advertiser;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\AdvertiserDeleteFromNetwork;
use App\Jobs\AdvertiserNotFoundToPending;
use App\Jobs\NetworkAdvertiserFetchStatusUpdateJob;
use App\Jobs\Rakuten\FetchAdvertiserJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use App\Plugins\Rakuten\RakutenTrait;

trait Rakuten
{
    use RakutenTrait;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handleRakuten($months)
    {

        $vars = $this->getRakutenStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $notAvailable = $vars['not_available'];
        $fetchLoop = $vars['fetch_loop_true'];
        $page = $vars['page'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

        Advertiser::where("source", $source)->update([
            "is_available" => $notAvailable
        ]);

        $advertisersData = $this->sendRakutenAdvertiserRequest($page);

        while ($fetchLoop)
        {
            echo $page;
            echo "\n\n";

            $advertiserPage = isset($advertisersData['_metadata']['total']) ? ceil($advertisersData['_metadata']['total'] / $advertisersData['_metadata']['limit']) : 0;

            if($page <= $advertiserPage)
            {
                FetchDailyData::updateOrCreate([
                    "path" => "RakutenAdvertiserJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "page" => $page,
                    "queue" => $queue,
                    "source" => $source,
                    "type" => Vars::ADVERTISER
                ], [
                    "name" => "Rakuten Advertiser Job",
                    "payload" => json_encode(["page" => $page]),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    "sort" => $this->setSortingFetchDailyData($source),
                ]);

            }
            else
            {
                $fetchLoop = $vars['fetch_loop_false'];
            }
            $page++;
        }

        $this->advertiserDataCompleteStatus($queue, $source);

    }

    private function getRakutenStaticVar(): array
    {
        $source = Vars::RAKUTEN;
        $name = strtoupper($source);
        $queue = Vars::RAKUTEN_ON_QUEUE;
        $notAvailable = Vars::ADVERTISER_NOT_AVAILABLE;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COMMAND",
            "queue_name" => $queue,
            "not_available" => $notAvailable,
            "fetch_loop_true" => true,
            "fetch_loop_false" => false,
            "page" => 1,
            "start_msg" => "FETCHING OF ADVERTISER AND ADVERTISER DETAIL INFORMATION HAS STARTED.",
            "end_msg" => "FETCHING OF ADVERTISER AND ADVERTISER DETAIL INFORMATION HAS BEEN COMPLETED.",
        ];
    }
}
