<?php

namespace App\Jobs;

use App\Models\AdvertiserApply;
use App\Models\AdvertiserPublisher;
use App\Traits\GenerateLink;
use App\Traits\Notification\Advertiser\Approval;
use App\Traits\Notification\Advertiser\JoinedAdvertiserHold;
use App\Traits\Notification\Advertiser\JoinedAdvertiserReject;
use App\Traits\Notification\Advertiser\Reject;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAdvertiserStatusOnFetchTimeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, GenerateLink, Approval, Reject, JoinedAdvertiserHold, JoinedAdvertiserReject;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->data;
        $advertiserIdz = $data['a_id'];
        if(is_string($advertiserIdz))
            $advertiserIdz = [$advertiserIdz];

        AdvertiserPublisher::whereIn("advertiser_sid", $advertiserIdz)->where('source', $data['source'])->orderBy('created_at')->chunk(5, function ($advertisers) use ($data) {
            foreach ($advertisers as $advertiser)
            {
                if ($data['status'] == AdvertiserPublisher::STATUS_HOLD && $advertiser->status == AdvertiserPublisher::STATUS_ACTIVE)
                {
                    $this->joinedAdvertiserHoldNotification($advertiser);
                }

                $advertiser->update([
                    'status' => $data['status'],
                ]);
            }
        });
    }
}
