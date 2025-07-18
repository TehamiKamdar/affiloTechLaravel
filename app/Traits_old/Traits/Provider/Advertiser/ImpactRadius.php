<?php

namespace App\Traits\Provider\Advertiser;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\AdvertiserDeleteFromNetwork;
use App\Jobs\AdvertiserNotFoundToPending;
use App\Jobs\ImpactRadius\AdvertiserJob;
use App\Jobs\NetworkAdvertiserFetchStatusUpdateJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use Illuminate\Support\Facades\Bus;
use Plugins\ImpactRadius\ImpactRadiusTrait;

trait ImpactRadius
{
    use ImpactRadiusTrait;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handleImpactRadius($months)
    {

        $vars = $this->getImpactStaticVar();

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

        $advertisersData = $this->sendImpactRadiusAdvertiserRequest($page);

        while ($fetchLoop)
        {
            echo $page;
//                $advertisersData = $this->sendImpactRadiusAdvertiserRequest($page);
            if(isset($advertisersData['@numpages']) && $page <= $advertisersData['@numpages'])
            {
                FetchDailyData::updateOrCreate([
                    "path" => "ImpactAdvertiserJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "page" => $page,
                    "queue" => $queue,
                    "source" => $source,
                    "type" => Vars::ADVERTISER
                ], [
                    "name" => "Impact Advertiser Job",
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

    private function getImpactStaticVar(): array
    {
        $source = Vars::IMPACT_RADIUS;
        $name = strtoupper($source);
        $queue = Vars::IMPACT_RADIUS_ON_QUEUE;
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
