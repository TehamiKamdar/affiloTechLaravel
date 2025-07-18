<?php

namespace App\Traits\Provider\Payment;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Admitad\TransactionJob;
use App\Models\FetchDailyData;
use Carbon\Carbon;
use Plugins\Admitad\AdmitadTrait;

trait Admitad
{

    public function handleAdmitad($months = 1)
    {

        $source = Vars::ADMITAD;
        $this->paymentStatusUpdate($source, $months);

    }

}
