<?php

namespace App\Console\Commands;

use App\Helper\Static\Vars;
use App\Jobs\Temp\Transaction as TempTransaction;
use App\Jobs\Temp\Transaction2 as TempTransaction2;
use App\Jobs\TempJob;
use App\Models\FetchDailyData;
use Illuminate\Console\Command;

class TempCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'temp-command';

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
    public function handle() {

    }
}
