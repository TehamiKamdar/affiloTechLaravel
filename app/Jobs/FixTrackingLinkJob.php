<?php

namespace App\Jobs;

use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use App\Services\Admin\PublisherManagement\Apply\MiscService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FixTrackingLinkJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $advertiser;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($advertiser)
    {
        $this->advertiser = $advertiser;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $advertiser = AdvertiserPublisher::find($this->advertiser['id'] ?? null);
        if($advertiser) {
            $service = new MiscService();
            $generateURL = $service->generateLinkNShortLink($this->advertiser);
            $trackURL = $generateURL['track_url'];
            $shortURL = $generateURL['short_url'];
            $this->advertiser->update([
                'tracking_url' => $trackURL,
                'tracking_url_short' => $shortURL,
            ]);
        }
    }
}
