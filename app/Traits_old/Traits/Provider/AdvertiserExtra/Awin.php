<?php

namespace App\Traits\Provider\AdvertiserExtra;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\AdvertiserDeleteFromNetwork;
use App\Jobs\AdvertiserNotFoundToPending;
use App\Jobs\Awin\AdvertiserImageUploadFetch;
use App\Jobs\NetworkAdvertiserExtraFetchStatusUpdateJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use Plugins\Awin\AwinTrait;

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
        $loopStart = $vars['loop_start'];
        $loopEnd = $vars['loop_end'];
        $limit = $vars['limit'];
        $skip = $vars['skip'];
        $advertiserActive = $vars['active'];
        $advertiserAvailable = $vars['available'];
        $limit20 = $vars['limit_20'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

//        $loop = $loopStart;
//        while ($loop)
//        {
//            $advertisers = Advertiser::select(['advertiser_id','fetch_logo_url','name'])->where("source", $source)
//                ->where(function($query) {
//                    $query->orWhereNull("logo");
//                    $query->orWhereNull("logo", "");
//                    $query->orWhereNull("logo", null);
//                })
//                ->whereNotNull("fetch_logo_url")
//                ->skip($skip)->take($limit)
//                ->get()->toArray();
//
//            foreach ($advertisers as $key => $advertiser)
//            {
//
//                FetchDailyData::updateOrCreate([
//                    "path" => "AwinAdvertiserImageUploadFetchJob",
//                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
//                    "key" => $key,
//                    "offset" => $skip,
//                    "queue" => $queue,
//                    "source" => $source,
//                    "type" => Vars::ADVERTISER_DETAIL
//                ], [
//                    "name" => "Awin Advertiser Image Upload Fetch Job",
//                    "payload" => json_encode($advertiser),
//                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
//                    "sort" => $this->setSortingFetchDailyData($source),
//                ]);
//
//            }
//
//            if(empty($advertisers))
//                $loop = $loopEnd;
//            else
//                $skip+=$limit;
//        }
//
//        $advertisersCount = Advertiser::where("source", $source)
//            ->where("is_available", $advertiserAvailable)
//            ->where("status", $advertiserActive)->count();
//        for ($no = 1; $no <= ceil($advertisersCount / $limit20); $no++)
//        {
//
//            $customLimit = $no * $limit20 - $limit20;
//            FetchDailyData::updateOrCreate([
//                "path" => "AwinAdvertiserDetailJob",
//                "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
//                "limit" => $customLimit,
//                "queue" => $queue,
//                "source" => $source,
//                "type" => Vars::ADVERTISER_DETAIL
//            ], [
//                "name" => "Awin Advertiser Detail Job",
//                "payload" => json_encode(['limit' => $customLimit]),
//                "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
//                "sort" => $this->setSortingFetchDailyData($source),
//            ]);
//
//        }

//        $this->advertiserNotFoundNDelete($queue, $source);

        $this->advertiserExtraDataCompleteStatus($queue, $source);

    }

    private function getAwinStaticVar(): array
    {
        $source = Vars::AWIN;
        $name = strtoupper($source);
        $queue = Vars::AWIN_ON_QUEUE;
        $active = Vars::ADVERTISER_STATUS_ACTIVE;
        $available = Vars::ADVERTISER_AVAILABLE;
        $limit = Vars::LIMIT_100;
        $skip = Vars::OFFSET_0;
        $limit_20 = Vars::LIMIT_20;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER EXTRA COMMAND",
            "queue_name" => $queue,
            "limit" => $limit,
            "limit_20" => $limit_20,
            "skip" => $skip,
            "active" => $active,
            "available" => $available,
            "loop_start" => true,
            "loop_end" => false,
            "start_msg" => "FETCHING OF ADDITIONAL DETAILS FOR THE ADVERTISER HAS STARTED.",
            "end_msg" => "FETCHING OF ADDITIONAL DETAILS FOR THE ADVERTISER HAS BEEN COMPLETED.",
        ];
    }
}
