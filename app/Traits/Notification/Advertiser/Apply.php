<?php

namespace App\Traits\Notification\Advertiser;

use App\Helper\Static\Vars;
use App\Models\Notification;

trait Apply
{
    public function applyNotification($advertiser, $publisherID)
    {
        Notification::updateOrCreate([
            "publisher_id" => $publisherID,
            "type" => "Apply Advertiser",
            "category" => "Advertiser Updates",
            "notification_header" => "Your application to join {$advertiser->name} was sent successfully.",
            "header" => "Your application to join {$advertiser->name} was sent successfully. <br /> <span class='title-foot mb-0 fs-14 color-light fw-400'>The status of {$advertiser->name} ({$advertiser->sid}) is currently pending... Read More</span>",
            "content" => "<p class='mb-20'>Hello,
                 <br /> <br />
                 Your request to promote {$advertiser->name} has been sent. The status of {$advertiser->name} ({$advertiser->sid}) is currently pending. Please wait for the approval.
                 <br /> <br />
                 Thank you for your interest in partnering with {$advertiser->name}.
                 <br /> <br />
                Regards,<br />
                ClickPrefer</p>
                ",
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT)
        ]);
    }
}
