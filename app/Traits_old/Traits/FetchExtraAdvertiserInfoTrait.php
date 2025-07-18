<?php

namespace App\Traits;

use App\Traits\Provider\ProviderBase;
use App\Jobs\Helper\IsNotAvailableAdvertiserTrait;
use App\Traits\Provider\AdvertiserExtra\Admitad as AdmitadProvider;
use App\Traits\Provider\AdvertiserExtra\Awin as AwinProvider;
use App\Traits\Provider\AdvertiserExtra\ImpactRadius as ImpactRadiusProvider;
use App\Traits\Provider\AdvertiserExtra\Rakuten as RakutenProvider;
use App\Traits\Provider\AdvertiserExtra\Tradedoubler as TradeDoublerProvider;
use Predis\Client;

trait FetchExtraAdvertiserInfoTrait
{
    use RequestTrait, JobTrait, Main, ProviderBase, IsNotAvailableAdvertiserTrait, AdmitadProvider, AwinProvider, ImpactRadiusProvider, RakutenProvider, TradeDoublerProvider;
}
