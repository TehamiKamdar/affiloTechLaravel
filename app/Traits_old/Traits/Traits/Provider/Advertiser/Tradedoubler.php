<?php

namespace App\Traits\Provider\Advertiser;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\AdvertiserDeleteFromNetwork;
use App\Jobs\AdvertiserNotFoundToPending;
use App\Jobs\NetworkAdvertiserFetchStatusUpdateJob;
use App\Jobs\Tradedoubler\AdvertiserDetailJob;
use App\Jobs\Tradedoubler\AdvertiserJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use Plugins\Traderdoubler\TradedoublerTrait;

trait Tradedoubler
{
    use TradedoublerTrait;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handleTradedoubler($months)
    {
        $vars = $this->getTradedoublerStaticVar();

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

        $sources = $this->getTradedoublerSourceList();
        $sourceIdz = is_array($sources) ? array_column($sources, "id") : [];

        if(empty($sourceIdz))
        {
            Methods::customTradedoubler($module, $sources);
        }
        else
        {
            foreach ($sourceIdz as $id)
            {
                $fetchLoop = $vars['fetch_loop_true'];
                $offset = $vars['offset'];

                if(empty($id))
                {
                    Methods::customTradedoubler($module, $exceptionMsg);
                }
                else
                {

                    $advertisersData = $this->sendTradedoublerAdvertiserRequest($id, $offset);

                    while ($fetchLoop)
                    {
                        echo "SOURCE: {$id} | OFFSET: {$offset}";
                        echo "\n\n";

                        FetchDailyData::updateOrCreate([
                            "path" => "TradedoublerAdvertiserJob",
                            "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                            "limit" => $limit,
                            "offset" => $offset,
                            "key" => $id,
                            "queue" => $queue,
                            "source" => $source,
                            "type" => Vars::ADVERTISER
                        ], [
                            "name" => "Tradedoubler Advertiser Job",
                            "payload" => json_encode(["offset" => $offset, 'source_id' => $id, 'limit' => $limit]),
                            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                            "sort" => $this->setSortingFetchDailyData($source),
                        ]);

                        if($advertisersData['total'] <= $offset)
                        {
                            $fetchLoop = $vars['fetch_loop_false'];
                        }
                        $offset += $limit;

                    }

                }
            }
        }

        $this->advertiserDataCompleteStatus($queue, $source);

    }

    private function getTradedoublerStaticVar(): array
    {
        $source = Vars::TRADEDOUBLER;
        $name = strtoupper($source);
        $queue = Vars::TRADEDOUBLER_ON_QUEUE;
        $notAvailable = Vars::ADVERTISER_NOT_AVAILABLE;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COMMAND",
            "queue_name" => $queue,
            "not_available" => $notAvailable,
            "fetch_loop_true" => true,
            "fetch_loop_false" => false,
            "offset" => 0,
            "limit" => Vars::LIMIT_20,
            "start_msg" => "FETCHING OF ADVERTISER AND ADVERTISER DETAIL INFORMATION HAS STARTED.",
            "end_msg" => "FETCHING OF ADVERTISER AND ADVERTISER DETAIL INFORMATION HAS BEEN COMPLETED.",
            "exception_msg" => "THE SOURCE ID PROVIDED COULD NOT BE FOUND. PLEASE ENSURE THAT THE CORRECT SOURCE ID IS PROVIDED AND TRY AGAIN."
        ];
    }

}
