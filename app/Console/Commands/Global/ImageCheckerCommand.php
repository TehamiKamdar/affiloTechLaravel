<?php

namespace App\Console\Commands\Global;

use App\Jobs\ImageExistJob;
use App\Models\Advertiser;
use Illuminate\Console\Command;

class ImageCheckerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check-image-exist';

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
        $advertisers = Advertiser::whereNotNull("logo")->get();
        foreach ($advertisers->chunk(100) as $advertiser)
        {
            ImageExistJob::dispatch($advertiser);
        }
    }
}
