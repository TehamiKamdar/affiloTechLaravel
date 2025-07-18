<?php

namespace App\Traits;

use App\Jobs\Helper\IsNotAvailableAdvertiserTrait;
use App\Traits\Provider\Offer\Admitad as AdmitadProvider;
use App\Traits\Provider\Offer\Awin as AwinProvider;
use App\Traits\Provider\Offer\ImpactRadius as ImpactRadiusProvider;
use App\Traits\Provider\Offer\Rakuten as RakutenProvider;
use App\Traits\Provider\Offer\Tradedoubler as TradedoublerProvider;
use App\Traits\Provider\ProviderBase;

trait FetchAdvertiserOfferTrait
{
    use RequestTrait, JobTrait, Main, IsNotAvailableAdvertiserTrait, ProviderBase, AdmitadProvider, AwinProvider, ImpactRadiusProvider, RakutenProvider, TradedoublerProvider;
}
