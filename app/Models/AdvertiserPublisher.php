<?php

namespace App\Models;

class AdvertiserPublisher extends BaseModel
{
    protected $connection = "mysql";
    const STATUS_NEW = "new",
        STATUS_NOT_ACTIVE = "not-joined",
        STATUS_ACTIVE = "joined",
        STATUS_PENDING = "pending",
        STATUS_REJECTED = "rejected",
        STATUS_ADMITAD_HOLD = "admitad_hold",
        STATUS_HOLD_CANCEL = "hold_cancel",
        STATUS_ACTIVE_CANCEL = "active_cancel",
        STATUS_HOLD = "hold";

    const GENERATE_LINK_EMPTY = 0,
        GENERATE_LINK_COMPLETE = 1,
        GENERATE_LINK_IN_PROCESS = 2;

    protected $table = "advertiser_publishers";
    protected $dates = ["updated_at", "created_at"];
    protected $fillable = [
        "advertiser_id",
        "publisher_id",
        "website_id",
        "source",
        "status",
        "locked_status",
        "is_generate_link",
        "is_tracking_generate",
        "tracking_url_short",
        "tracking_url",
        "click_through_url",
        "applied_at",
        "approved_at",
        "advertiser_sid",
        "approver_id",
        "reject_approve_reason",
        "tracking_url_long"
    ];

    public function advertiser()
    {
        return $this->hasOne(Advertiser::class, "id", "advertiser_id");
    }

    public function publisher()
    {
        return $this->hasOne(User::class, "publisher_id", "publisher_id");
    }

    public function website()
    {
        return $this->hasOne(Website::class, "id", "website_id");
    }
}
