<?php

namespace App\Traits;

use App\Helper\Static\Vars;
use App\Models\FetchDailyData;
use Carbon\Carbon;
use Predis\Client;

trait Main
{
    public function setSortingFetchDailyData($source)
    {
        $fetch = FetchDailyData::select('sort')->where('source', $source)->whereDate('created_at', Carbon::today())->latest('created_at')->first();
        if(empty($fetch))
        {
            return 1;
        }
        else
        {
            return $fetch->sort + 1;
        }
    }
}
