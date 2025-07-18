<?php

namespace App\Traits\Notification\Advertiser;

use App\Helper\Static\Methods;
use App\Helper\Static\Vars;
use App\Jobs\Mail\Advertiser\JoinedAdvertiserHoldJob;
use App\Models\EmailJob;
use App\Models\Notification;

trait JoinedAdvertiserHold
{
    public function joinedAdvertiserHoldNotification($advertiser)
    {
        Notification::updateOrCreate([
            "publisher_id" => $advertiser->publisher_id,
            "type" => "Joined Advertiser Hold",
            "category" => "Advertiser Updates",
            "notification_header" => "{$advertiser->advertiser_name} ({$advertiser->advertiser_sid}) is moved to HOLD.",
            "header" => "{$advertiser->advertiser_name} ({$advertiser->advertiser_sid}) is moved to HOLD. <br /> <span class='title-foot mb-0 fs-14 color-light fw-400'>Your joined advertiser {$advertiser->advertiser_name} is moved to HOLD.... Read More</span>",
            "content" => "<p class='mb-20'>Hello,
                 <br /> <br />
                 Your joined advertiser {$advertiser->advertiser_name}  ({$advertiser->advertiser_sid}) status is moved to HOLD. There may be numerous factors that result in a HOLD position, please contact LinksCircle support to understand the issue.
                 <br /> <br />
                Regards,<br />
                LinksCircle</p>
                ",
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT)
        ]);

//        EmailJob::create([
//            'name' => 'Joined Advertiser Hold Job',
//            'path' => 'JoinedAdvertiserHoldJob',
//            'payload' => json_encode($advertiser->with('publisher')->first()),
//            'date' => now()->format(Vars::CUSTOM_DATE_FORMAT)
//        ]);

    }
}
