<?php

namespace App\Traits;

use App\Traits\Provider\ProviderBase;
use App\Traits\Provider\Transaction\Admitad as AdmitadProvider;
use App\Traits\Provider\Transaction\Awin as AwinProvider;
use App\Traits\Provider\Transaction\ImpactRadius as ImpactRadiusProvider;
use App\Traits\Provider\Transaction\Rakuten as RakutenProvider;
use App\Traits\Provider\Transaction\Tradedoubler as TradedoublerProvider;

trait FetchAdvertiserTransactionTrait
{
    use RequestTrait, JobTrait, Main, ProviderBase, AdmitadProvider, AwinProvider, ImpactRadiusProvider, RakutenProvider, TradedoublerProvider;
}
