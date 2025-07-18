<?php

namespace App\Plugins\Rakuten;

use App\Helper\Static\Vars;
use App\Models\Advertiser as AdvertiserModel;
use App\Traits\JobTrait;
use App\Traits\MediaTrait;
use App\Traits\RequestTrait;

class Base
{
    use RequestTrait, JobTrait, MediaTrait, RakutenTrait;

    protected string|null $name, $source = null;

    public function __construct()
    {
        $this->source = Vars::RAKUTEN;
        $this->name = strtoupper($this->source);
    }

    protected function getRakutenAdvertiserStaticVar(): array
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

    protected function getRakutenAdvertiserDetailStaticVar(): array
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

    protected function getRakutenCouponStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COUPON PLUGIN",
        ];
    }

    protected function getRakutenTransactionStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER TRANSACTION PLUGIN",
        ];
    }
}
