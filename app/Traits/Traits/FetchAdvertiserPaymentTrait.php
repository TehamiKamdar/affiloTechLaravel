<?php

namespace App\Traits;

use App\Traits\Provider\ProviderBase;
use App\Traits\Provider\Payment\Admitad as AdmitadProvider;
use App\Traits\Provider\Payment\Awin as AwinProvider;
use App\Traits\Provider\Payment\ImpactRadius as ImpactRadiusProvider;
use App\Traits\Provider\Payment\Rakuten as RakutenProvider;
use App\Traits\Provider\Payment\Tradedoubler as TradedoublerProvider;

trait FetchAdvertiserPaymentTrait
{
    use RequestTrait, JobTrait, Main, ProviderBase, AdmitadProvider, AwinProvider, ImpactRadiusProvider, RakutenProvider, TradedoublerProvider;
}
