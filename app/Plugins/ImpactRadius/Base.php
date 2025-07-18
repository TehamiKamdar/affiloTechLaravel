<?php

namespace App\Plugins\ImpactRadius;

use App\Helper\Static\Vars;
use App\Models\Advertiser as AdvertiserModel;
use App\Traits\JobTrait;
use App\Traits\MediaTrait;
use App\Traits\RequestTrait;

class Base
{
    use RequestTrait, JobTrait, MediaTrait, ImpactRadiusTrait;

    protected string|null $name, $source = null;

    public function __construct()
    {
        $this->source = Vars::IMPACT_RADIUS;
        $this->name = strtoupper($this->source);
    }

    protected function getImpactAdvertiserStaticVar(): array
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

    protected function getImpactCouponStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER COUPON PLUGIN",
        ];
    }

    protected function getImpactTransactionStaticVar(): array
    {
        $source = $this->source;
        $name = $this->name;
        $limit = Vars::IMPACT_TRANSACTION_LIMIT;

        return [
            "source" => $source,
            "module_name" => "{$name} ADVERTISER TRANSACTION PLUGIN",
            "limit" => $limit
        ];
    }
}
