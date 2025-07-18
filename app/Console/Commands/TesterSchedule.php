<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helper\Static\Vars;
use App\Helper\Static\Methods;
use App\Models\FetchDailyData;
use App\Models\Setting;
use App\Traits\Main;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use App\Models\SyncData as SyncDataModel;

class TesterSchedule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:schedule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $source = 'Quk';
        $url = env("APP_SERVER_API_URL");
        $url = "{$url}api/sync-transactions?source={$source}";
        $start_date = now()->subDays(90)->format("Y-m-d");
        $end_date = now()->addDays(1)->format("Y-m-d");
        $url = "{$url}&start_date=$start_date&end_date=$end_date";
        $response = Http::get($url);
        $this->info($url);
       
    }
}
