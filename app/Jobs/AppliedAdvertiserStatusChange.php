<?php

namespace App\Jobs;

use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AppliedAdvertiserStatusChange implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $idz;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($idz)
    {
        $this->idz = $idz;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        AdvertiserPublisher::whereIn('id', $this->idz)->update([
            "is_tracking_generate" => AdvertiserPublisher::GENERATE_LINK_COMPLETE
        ]);
    }
}
