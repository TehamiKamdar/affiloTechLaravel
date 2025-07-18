<?php
namespace App\Models;

class CouponTrackingDetail extends BaseModel
{
    protected $table = "coupon_tracking_details";

    protected $dates = [
        "updated_at",
        "created_at",
    ];

    protected $fillable = [
        "advertiser_id",
        "publisher_id",
        "website_id",
        "tracking_id",
        "ip_address",
        "operating_system",
        "browser",
        "device",
        "referer_url",
        "country",
        "iso2",
        "region",
        "city",
        "zipcode",
        "is_deleted",
        "is_new",
    ];

    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class, "advertiser_id", "id");
    }
}
