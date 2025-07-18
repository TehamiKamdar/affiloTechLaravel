<?php

namespace App\Traits\Notification\Advertiser;

use App\Helper\Static\Vars;
use App\Models\Notification;

trait Reject
{
    public function rejectNotification($advertiser)
    {

        Notification::updateOrCreate([
            "publisher_id" => $advertiser->publisher_id,
            "type" => "Advertiser Rejected",
            "category" => "Rejections",
            "notification_header" => "{$advertiser->advertiser_name} ({$advertiser->advertiser_sid}) rejected your affiliation request.",
            "header" => "{$advertiser->advertiser_name} ({$advertiser->advertiser_sid}) rejected your affiliation request. <br /> <span class='title-foot mb-0 fs-14 color-light fw-400'>Your application to join {$advertiser->advertiser_name} programme has been rejected... Read More</span>",
            "content" => "<p class='mb-20'>Hello,
                 <br /> <br />
                 Your application to join {$advertiser->advertiser_name} programme has been rejected.
                 <br /> <br />
                 There may be numerous factors that result in a declined application, with some of the most common listed below:
                 <br />
                 <ul>
                    <li>Incomplete website</li>
                    <li>Irrelevant website and/or content</li>
                    <li>Restricted traffic type and/or source</li>
                    <li>Quality standards not met</li>
                    <li>No response to request for further information</li>
                 </ul>
                 <br /> <br />
                Regards,<br />
                LinksCircle</p>
                ",
            "date" => now()->format(Vars::CUSTOM_DATE_FORMAT)
        ]);

    }
}
