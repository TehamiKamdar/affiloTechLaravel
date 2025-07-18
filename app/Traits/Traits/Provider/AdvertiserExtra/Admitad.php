<?php

namespace App\Traits\Provider\AdvertiserExtra;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Admitad\MultiStatusCheckJob;
use App\Jobs\NetworkAdvertiserExtraFetchStatusUpdateJob;
use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use App\Models\DailyDataFetch;
use App\Models\FetchDailyData;
use App\Models\Website;
use App\Plugins\Admitad\AdmitadTrait;

trait Admitad
{
    use AdmitadTrait;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handleAdmitad($months)
    {

        $vars = $this->getAdmitadStaticVar();
        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $module = $vars['module_name'];
        $timer = $vars['timer'];
        $timing = $vars['timing'];
        $limit = $vars['limit'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

//        $this->checkApplyAdvertiserStatus($queue, $source);
//        $this->advertiserNotFoundNDelete($queue, $source);
        $this->advertiserExtraDataCompleteStatus($queue, $source);

    }

    private function checkApplyAdvertiserStatus($queue, $source) {
        $arrList = [];
        $advertisers = AdvertiserPublisher::with('advertiser')->where("source", $source)->whereIn("status", [AdvertiserPublisher::STATUS_PENDING, AdvertiserPublisher::STATUS_ADMITAD_HOLD])->get();
        foreach ($advertisers as $advertiser)
        {
            $website = Website::select('admitad_wid')->where('id', $advertiser->website_id)->first();
            $wID = $website->admitad_wid ?? null;
            $arrList[$advertiser->advertiser->advertiser_id][] = $wID;
        }

        foreach ($arrList as $advertiser_id => $itemChunks)
        {
            foreach (array_chunk($itemChunks, 30) as $key => $itemChunk)
            {
                FetchDailyData::updateOrCreate([
                    "path" => "AdmitadMultiStatusCheckJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "key" => $key,
                    "advertiser_id" => $advertiser_id,
                    "queue" => $queue,
                    "source" => $source
                ], [
                    "name" => "Admitad Multi Status Check Job",
                    "payload" => json_encode(['website_id' => implode(",", $itemChunk), 'advertiser_id' => $advertiser_id]),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    "sort" => $this->setSortingFetchDailyData($source),
                ]);

            }
        }
    }

    private function getAdmitadStaticVar(): array
    {
        $source = Vars::ADMITAD;
        $name = strtoupper($source);
        $queue = Vars::ADMITAD_ON_QUEUE;
        $limit = Vars::LIMIT_10;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER EXTRA COMMAND",
            "queue_name" => $queue,
            "limit" => $limit,
            "timer" => 1,
            "timing" => 20,
            "start_msg" => "FETCHING OF ADDITIONAL DETAILS FOR THE ADVERTISER HAS STARTED.",
            "end_msg" => "FETCHING OF ADDITIONAL DETAILS FOR THE ADVERTISER HAS BEEN COMPLETED.",
        ];
    }

}
