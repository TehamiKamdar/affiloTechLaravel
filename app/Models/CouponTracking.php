<?php
namespace App\Models;

class CouponTracking extends BaseModel
{
    protected $table = "coupon_trackings";

    protected $dates = [
        "updated_at",
        "created_at",
    ];

    protected $fillable = [
        "advertiser_id",
        "publisher_id",
        "website_id",
        "coupon_id",
        "click_through_url",
        "tracking_url",
        "hits",
        "unique_visitor",
        "is_deleted",
    ];

    public function advertiser()
    {
        return $this->hasOne(Advertiser::class, "id", "advertiser_id");
    }

    public function publisher()
    {
        return $this->hasOne(User::class, "id", "publisher_id");
    }

    public function website()
    {
        return $this->hasOne(Website::class, "id", "website_id");
    }
}
