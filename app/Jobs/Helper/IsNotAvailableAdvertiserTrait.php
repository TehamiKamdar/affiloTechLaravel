<?php

namespace App\Jobs\Helper;

use App\Helper\Static\Vars;
use App\Jobs\AdvertiserDeleteFromNetwork;
use App\Jobs\AdvertiserNotFoundToPending;
use App\Models\Advertiser;
use Illuminate\Support\Facades\Bus;

trait IsNotAvailableAdvertiserTrait
{
    protected $jobsArr = [];

    public function checkAdvertiserIsNotAvailable($source)
    {
        Advertiser::select('sid','advertiser_id','source')->where("source", $source)->where("is_available", Vars::ADVERTISER_NOT_AVAILABLE)->chunk(100, function ($advertisers) {

            $this->jobsArr[] = new AdvertiserNotFoundToPending($advertisers);
            $this->jobsArr[] = new AdvertiserDeleteFromNetwork($advertisers);

        });

        return $this->jobsArr;
    }
}
