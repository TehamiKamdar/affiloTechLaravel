<?php

namespace App\Console\Commands;

use App\Models\Advertiser;
use Illuminate\Console\Command;

class SidCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sid-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $advertisers = Advertiser::where('sid',0)->get();
        if(count($advertisers)>0){
            foreach($advertisers as $advertiser){
                $rand = rand(00000000,999999999);
                $advertiser->sid = $rand;
                $advertiser->update();
            }
        }
    }
}
