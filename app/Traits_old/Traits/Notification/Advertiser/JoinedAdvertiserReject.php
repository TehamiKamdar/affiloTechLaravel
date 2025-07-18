<?php

namespace App\Traits\Notification\Advertiser;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Mail\Advertiser\JoinedAdvertiserRejectedJob;
use App\Models\EmailJob;
use App\Models\Notification;

trait JoinedAdvertiserReject
{
    use Base;

    public function joinedAdvertiserRejectNotification($advertiser)
    {
        Notification::updateOrCreate([
            "publisher_id" => $advertiser->publisher_id,
            "type" => "Joined Advertiser Rejected",
            "category" => "Advertiser Updates",
            "notification_header" => "{$advertiser->advertiser_name} ({$advertiser->advertiser_sid}) is moved to Rejected.",
            "header" => "{$advertiser->advertiser_name} ({$advertiser->advertiser_sid}) is moved to Rejected. <br /> <span class='title-foot mb-0 fs-14 color-light fw-400'>Your joined advertiser {$advertiser->advertiser_name} is moved to declined.... Read More</span>",
            "content" => "<p class='mb-20'>Hello,
                 <br /> <br />
                 Your joined advertiser {$advertiser->advertiser_name}  ({$advertiser->advertiser_sid}) status is moved to Rejected. There may be numerous factors that result in a decline position, please contact ClickPrefer support to understand the issue.
                 <br /> <br />
                Regards,<br />
                ClickPrefer</p>
                ",
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT)
        ]);

        EmailJob::create([
            'name' => 'Joined Advertiser Rejected Job',
            'path' => 'JoinedAdvertiserRejectedJob',
            'payload' => json_encode($advertiser->with('publisher')->first()),
            'date' => now()->format("Y-m-d H:i")
        ]);

    }
}
