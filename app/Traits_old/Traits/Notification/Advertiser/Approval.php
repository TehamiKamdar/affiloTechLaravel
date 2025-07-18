<?php

namespace App\Traits\Notification\Advertiser;

use App\Helper\Static\Vars;
use App\Models\Notification;

trait Approval
{
    public function approvalNotification($advertiser)
    {
        $url = route("publisher.view-advertiser", ['sid' => $advertiser->advertiser_sid]);
        Notification::updateOrCreate([
            "publisher_id" => $advertiser->publisher_id,
            "type" => "Advertiser Approved",
            "category" => "Approvals",
            "notification_header" => "{$advertiser->advertiser_name} ({$advertiser->advertiser_sid}) approved your affiliation request.",
            "header" => "{$advertiser->advertiser_name} ({$advertiser->advertiser_sid}) approved your affiliation request. <br /> <span class='title-foot mb-0 fs-14 color-light fw-400'>You are now approved onto the {$advertiser->advertiser_name} Affiliate programme... Read More</span>",
            "content" => "<p class='mb-20'>Hello,
                 <br /> <br />
                 You are now approved onto the {$advertiser->advertiser_name} Affiliate programme!
                 <br /> <br />
                 To view the advertiser profile simply click on the following link:
                 <br />
                 <a href='{$url}' target='_blank'>{$url}</a>
                 <br /> <br />
                Regards,<br />
                ClickPrefer</p>
                ",
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT)
        ]);
    }
}
