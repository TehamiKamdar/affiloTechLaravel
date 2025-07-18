<?php

namespace App\Traits\Network;

use Illuminate\Support\Facades\Http;

trait LinkbuxBase
{
    protected string $API_KEY = "gZFUlXlpTraC7AsN",
                     $BASE_PATH = "https://www.linkbux.com/api.php";

    protected function makeRequest($url, $params = [])
    {
        return Http::timeout(600)->get($url, $params);
    }

}
