<?php

namespace App\Plugins\Awin;

use App\Helper\Static\Vars;
use App\Models\Advertiser as AdvertiserModel;
use App\Traits\JobTrait;
use App\Traits\MediaTrait;
use App\Traits\RequestTrait;

class Base
{
    use RequestTrait, JobTrait, MediaTrait, AwinTrait;

    protected string|null $name, $source = null;

    public function __construct()
    {
        $this->source = Vars::AWIN;
        $this->name = strtoupper($this->source);
    }

    protected function getAwinAdvertiserStaticVar(): array
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

    protected function getAwinAdvertiserDetailStaticVar(): array
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

    protected function getAwinCouponStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COUPON PLUGIN",
        ];
    }

    protected function getAwinTransactionStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER TRANSACTION PLUGIN",
        ];
    }
}
