<?php

namespace App\Plugins\Admitad;

use App\Helper\Static\Vars;
use App\Models\Advertiser as AdvertiserModel;
use App\Models\Mix;
use App\Traits\JobTrait;
use App\Traits\MediaTrait;
use App\Traits\RequestTrait;

class Base
{
    use RequestTrait, JobTrait, MediaTrait, AdmitadTrait;

    protected string|null $name, $source = null;

    public function __construct()
    {
        $this->source = Vars::ADMITAD;
        $this->name = strtoupper($this->source);
    }

    protected function getAdmitadCategoryStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER CATEGORY PLUGIN",
            "type" => Mix::CATEGORY
        ];
    }

    protected function getAdmitadPromotionStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER PROMOTION PLUGIN",
            "type" => Mix::PROMOTIONAL_METHOD
        ];
    }

    protected function getAdmitadAdvertiserStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;
        $available = Vars::ADVERTISER_AVAILABLE;
        $commissionLinkscircle = Vars::COMMISSION_LINKSCIRCLE;
        $defaultCreated = Vars::DEFAULT_GENERATED;
        $createdBy = Vars::CRON_JOB_CREATED;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER PLUGIN",
            "available" => $available,
            "type" => AdvertiserModel::API,
            "commission_linkscircle" => $commissionLinkscircle,
            "default_created" => $defaultCreated,
            "created_by" => $createdBy
        ];
    }

    protected function getAdmitadAdvertiserDetailStaticVar(): array
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

    protected function getAdmitadCouponStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COUPON PLUGIN",
        ];
    }

    protected function getAdmitadTransactionStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER TRANSACTION PLUGIN",
        ];
    }
}
