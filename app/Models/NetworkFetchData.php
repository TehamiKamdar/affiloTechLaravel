<?php

namespace App\Models;

class NetworkFetchData extends BaseModel
{
    const NETWORK_DISABLE = 0;
    const NETWORK_ACTIVE = 1;

    const NOT_PROCESSING = 0;
    const COMPLETED = 1;
    const PROCESSING = 2;
    const NOT_FOUND = 3;

    protected $table = "network_fetch_data";

    /**
     * The attributes that used for dates.
     *
     * @var array<string, string>
     */
    protected $dates = [
        'updated_at',
        'created_at',
    ];

    protected $fillable = [
        "name",
        "status",
        "advertiser_schedule_status",
        "advertiser_extra_schedule_status",
        "advertiser_coupon_schedule_status",
        "advertiser_transaction_schedule_status",
        "advertiser_transaction_short_status",
        "advertiser_payment_status",
        "last_updated_advertiser",
        "last_updated_advertiser_extra",
        "last_updated_advertiser_offer",
        "last_updated_advertiser_transaction",
        "last_updated_advertiser_transaction_short",
        "last_updated_advertiser_payment",
        "sort",
    ];
}
