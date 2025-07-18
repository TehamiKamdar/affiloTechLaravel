<?php
namespace App\Models;

class DeeplinkTrackingDetail extends BaseModel 
{
    protected $table = "deeplink_tracking_details";  // Table name
    protected $dates = ["updated_at", "created_at"]; // Date fields
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
        "is_new"
    ];  // Fillable attributes

    public function advertiser() 
    {
        return $this->hasOne(Advertiser::class, "id", "advertiser_id");  // Relationship with the Advertiser model
    }
}
