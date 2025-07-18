<?php

namespace App\Traits\Provider\Advertiser;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\NetworkAdvertiserFetchStatusUpdateJob;
use App\Models\Advertiser;
use App\Models\FetchDailyData;
use Plugins\Admitad\AdmitadTrait;

trait Admitad
{
    use AdmitadTrait;

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handleAdmitad($months)
    {
        $vars = $this->getAdmitadStaticVar();

        $source = $vars['source'];
        $module = $vars['module_name'];
        $notAvailable = $vars['not_available'];
        $startMsg = $vars['start_msg'];
        $endMsg = $vars['end_msg'];

        Advertiser::where("source", $source)->update([
            "is_available" => $notAvailable
        ]);

        $this->fetchPromotionalMethod();
        $this->fetchCategory();
        $this->fetchAdvertiser();

    }

    private function fetchCategory()
    {

        $vars = $this->getAdmitadStaticVar();

        $source = $vars['source'];
        $limit = $vars['category_limit'];
        $queue = $vars['queue_name'];
        $categories = $this->sendAdmitadCategoryRequest(0);

        if(isset($categories["_meta"]['count']))
        {
            for ($job = 0; $job < ceil($categories["_meta"]['count'] / $limit); $job++)
            {
                $offset = $job * $limit;
                FetchDailyData::updateOrCreate([
                    "path" => "AdmitadCategoryJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "offset" => $offset,
                    "queue" => $queue,
                    "source" => $source,
                    "type" => Vars::ADVERTISER
                ], [
                    "name" => "Admitad Category Job",
                    "payload" => json_encode(["offset" => $offset]),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    "sort" => $this->setSortingFetchDailyData($source)
                ]);

            }
        }
    }

    private function fetchPromotionalMethod()
    {

        $vars = $this->getAdmitadStaticVar();

        $source = $vars['source'];
        $limit = $vars['promotion_method_limit'];
        $queue = $vars['queue_name'];
        $methods = $this->sendAdmitadPromotionalMethodRequest(0);

        if(isset($methods["_meta"]['count']))
        {
            for ($job = 0; $job < ceil($methods["_meta"]['count'] / $limit); $job++)
            {
                $offset = $job * $limit;
                FetchDailyData::updateOrCreate([
                    "path" => "AdmitadPromotionalMethodJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "offset" => $offset,
                    "queue" => $queue,
                    "source" => $source,
                    "type" => Vars::ADVERTISER
                ], [
                    "name" => "Admitad Promotional Method Job",
                    "payload" => json_encode(["offset" => $offset]),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    "sort" => $this->setSortingFetchDailyData($source)
                ]);

            }
        }
    }

    private function fetchAdvertiser()
    {
        $vars = $this->getAdmitadStaticVar();

        $source = $vars['source'];
        $queue = $vars['queue_name'];
        $offset = $vars['offset'];
        $limit = $vars['advertiser_method_limit'];

        $wid = $vars['wid'];
        $advertisersData = $this->sendAdmitadAdvertiserRequest($wid, $offset);

        if(isset($advertisersData["_meta"]['count']))
        {
            for ($job = 0; $job < ceil($advertisersData["_meta"]['count'] / $limit); $job++)
            {
                $offset = $job * $limit;
                FetchDailyData::updateOrCreate([
                    "path" => "AdmitadAdvertiserJob",
                    "process_date" => now()->format(Vars::CUSTOM_DATE_FORMAT_3),
                    "website_id" => $wid,
                    "offset" => $offset,
                    "queue" => $queue,
                    "source" => $source,
                    "type" => Vars::ADVERTISER
                ], [
                    "name" => "Admitad Advertiser Job",
                    "payload" => json_encode(["offset" => $offset, "website_id" => $wid]),
                    "date" => now()->format(Vars::CUSTOM_DATE_FORMAT_2),
                    "sort" => $this->setSortingFetchDailyData($source)
                ]);

            }
        }

        $this->advertiserDataCompleteStatus($queue, $source);

    }

    private function getAdmitadStaticVar(): array
    {
        $source = Vars::ADMITAD;
        $name = strtoupper($source);
        $queue = Vars::ADMITAD_ON_QUEUE;
        $notAvailable = Vars::ADVERTISER_NOT_AVAILABLE;
        $configs = $this->getAdmitadConfigData();
        $wid = $configs["ad_space_id"];

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COMMAND",
            "queue_name" => $queue,
            "not_available" => $notAvailable,
            "fetch_loop_true" => true,
            "fetch_loop_false" => false,
            "offset" => 0,
            "category_limit" => Vars::ADMITAD_CATEGORIES_LIMIT,
            "promotion_method_limit" => Vars::ADMITAD_PROMOTIONAL_METHOD_LIMIT,
            "advertiser_method_limit" => Vars::ADMITAD_ADVERTISER_LIMIT,
            "wid" => $wid,
            "start_msg" => "FETCHING OF ADVERTISER AND ADVERTISER DETAIL INFORMATION HAS STARTED.",
            "end_msg" => "FETCHING OF ADVERTISER AND ADVERTISER DETAIL INFORMATION HAS BEEN COMPLETED.",
        ];
    }
}
