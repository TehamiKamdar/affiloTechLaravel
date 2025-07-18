<?php

namespace App\Traits\Provider\Payment;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Admitad\TransactionJob;
use App\Models\FetchDailyData;
use Carbon\Carbon;
use App\Plugins\Admitad\AdmitadTrait;

trait Awin
{

    public function handleAwin($months = 1)
    {

        $source = Vars::AWIN;
        $this->paymentStatusUpdate($source, $months);

    }

}
