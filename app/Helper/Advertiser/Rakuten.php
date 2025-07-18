<?php

namespace App\Helper\Advertiser;

class Rakuten
{
    const FIELDS = [
        "name",
        "url",
        "click_through_url",
        "short_description",
        "deeplink_enabled",
        "source",
        "type"
    ];

    public static function init($field)
    {
        return in_array($field, self::FIELDS);
    }
}
