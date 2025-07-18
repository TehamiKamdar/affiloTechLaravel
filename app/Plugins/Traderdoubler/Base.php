<?php

namespace App\Plugins\Traderdoubler;

use App\Helper\Static\Vars;
use App\Models\Advertiser as AdvertiserModel;
use App\Traits\JobTrait;
use App\Traits\MediaTrait;

class Base
{
    use RequestTrait, JobTrait, MediaTrait, TradedoublerTrait;

    protected string|null $name, $source = null;

    public function __construct()
    {
        $this->source = Vars::TRADEDOUBLER;
        $this->name = strtoupper($this->source);
    }

    protected function getTradedoublerAdvertiserStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;
        $available = Vars::ADVERTISER_AVAILABLE;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER PLUGIN",
            "available" => $available,
            "type" => AdvertiserModel::API,
        ];
    }

    protected function getTradedoublerAdvertiserDetailStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;
        $commissionLinkscircle = Vars::COMMISSION_LINKSCIRCLE;
        $defaultCreated = Vars::DEFAULT_GENERATED;
        $createdBy = Vars::CRON_JOB_CREATED;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER DETAIL PLUGIN",
            "commission_linkscircle" => $commissionLinkscircle,
            "default_created" => $defaultCreated,
            "created_by" => $createdBy,
        ];
    }

    protected function getTradedoublerCouponStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COUPON PLUGIN",
        ];
    }

    protected function getTradedoublerTransactionStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;
        $queue = Vars::TRADEDOUBLER_TRANSACTION_ON_QUEUE;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER TRANSACTION PLUGIN",
            "queue_name" => $queue,
            "fetch_loop_true" => true,
            "fetch_loop_false" => false,
            "offset" => 0,
            "limit" => Vars::LIMIT_20,
            "start_msg" => "FETCHING OF TRANSACTION INFORMATION HAS STARTED.",
            "end_msg" => "FETCHING OF TRANSACTION INFORMATION HAS BEEN COMPLETED.",
            "exception_msg" => "THE SOURCE ID PROVIDED COULD NOT BE FOUND. PLEASE ENSURE THAT THE CORRECT SOURCE ID IS PROVIDED AND TRY AGAIN."
        ];
    }
}
