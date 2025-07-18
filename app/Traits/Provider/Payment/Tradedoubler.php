<?php

namespace App\Traits\Provider\Payment;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Admitad\TransactionJob;
use App\Models\FetchDailyData;
use Carbon\Carbon;
use App\Plugins\Admitad\AdmitadTrait;
use App\Plugins\Traderdoubler\TradedoublerTrait;

trait Tradedoubler
{
    use TradedoublerTrait;

    public function handleTradedoubler($months = 1)
    {

        $source = Vars::TRADEDOUBLER;
        $this->paymentStatusUpdate($source, $months);

    }

}
