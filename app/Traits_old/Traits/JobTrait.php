<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait JobTrait
{
    public $no = 1;
    public function changeJobTime($seonds = 20)
    {
        sleep($seonds);
    }

    public function addJobDelay($default, $connection = null)
    {
        return \Queue::getRedis()
            ->connection($connection)
            ->zrange('queues:'.$default.':delayed', 0, -1);
    }
}
