<?php

namespace App\Traits;

use App\Jobs\Helper\IsNotAvailableAdvertiserTrait;
use App\Traits\Provider\Advertiser\Admitad as AdmitadProvider;
use App\Traits\Provider\Advertiser\Awin as AwinProvider;
use App\Traits\Provider\Advertiser\ImpactRadius as ImpactRadiusProvider;
use App\Traits\Provider\Advertiser\Rakuten as RakutenProvider;
use App\Traits\Provider\Advertiser\Tradedoubler as TradeDoublerProvider;
use App\Traits\Provider\ProviderBase;

trait FetchAdvertiserTrait
{
    use RequestTrait, JobTrait, Main, IsNotAvailableAdvertiserTrait, ProviderBase, AdmitadProvider, AwinProvider, ImpactRadiusProvider, RakutenProvider, TradeDoublerProvider;
}
