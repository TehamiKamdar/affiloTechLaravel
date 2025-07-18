<?php
namespace App\Models;

class TrackingDetail extends BaseModel
{
    // The table associated with the model
    protected $table = "tracking_details";

    /**
     * The attributes that are used for dates.
     *
     * @var array<string, string>
     */
    protected $dates = [
        'updated_at',  // Timestamp for updates
        'created_at',  // Timestamp for creation
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "advertiser_id",   // Foreign key for the Advertiser
        "publisher_id",    // Foreign key for the Publisher
        "website_id",      // Foreign key for the Website
        "tracking_id",     // Identifier for tracking
        "ip_address",      // IP address of the visitor
        "operating_system", // Operating system of the visitor
        "browser",          // Browser of the visitor
        "device",           // Device used by the visitor
        "referer_url",      // Referer URL
        "country",          // Country of the visitor
        "iso2",             // Country ISO code
        "region",           // Region of the visitor
        "city",             // City of the visitor
        "zipcode",          // Zipcode of the visitor
        "is_deleted",       // Flag indicating if the record is deleted
        "is_new",           // Flag indicating if the record is new
    ];

    /**
     * Get the advertiser associated with this tracking detail.
     */
    public function advertiser()
    {
        return $this->hasOne(Advertiser::class, "id", "advertiser_id"); // One-to-one relationship with Advertiser model
    }
}
