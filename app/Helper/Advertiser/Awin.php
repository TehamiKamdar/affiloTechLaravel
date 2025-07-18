<?php

namespace App\Helper\Advertiser;

class Awin
{
    const FIELDS = [
        "name",
        "url",
        "primary_regions",
        "country_full_name",
        "currency_code",
        "click_through_url",
        "short_description",
        "average_payment_time",
        "valid_domains",
        "validation_days",
        "epc",
        "deeplink_enabled",
        "logo"
    ];

    public static function init($field)
    {
        return in_array($field, self::FIELDS);
    }
}
