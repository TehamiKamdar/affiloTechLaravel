<?php
namespace App\Models;

class Tracking extends BaseModel
{
    // The table associated with the model
    protected $table = "trackings";

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
        "advertiser_id",     // Foreign key for the Advertiser
        "publisher_id",      // Foreign key for the Publisher
        "website_id",        // Foreign key for the Website
        "sub_id",            // Sub identifier for tracking
        "click_through_url", // URL for click tracking
        "tracking_url_long", // Full tracking URL
        "tracking_url_short", // Shortened tracking URL
        "tracking_url",      // The final tracking URL
        "hits",              // Number of hits (clicks)
        "unique_visitor",    // Number of unique visitors
        "is_deleted",        // Flag to mark if the tracking is deleted
    ];

    /**
     * Get the advertiser associated with this tracking.
     */
    public function advertiser()
    {
        return $this->hasOne(Advertiser::class, 'id', 'advertiser_id'); // One-to-one relationship with Advertiser model
    }

    /**
     * Get the advertiser application associated with this tracking.
     */
    public function advertiserApply()
    {
        return $this->hasOne(AdvertiserPublisher::class, 'internal_advertiser_id', 'advertiser_id'); // One-to-one relationship with AdvertiserApply model
    }

    /**
     * Get the publisher associated with this tracking.
     */
    public function publisher()
    {
        return $this->hasOne(User::class, 'id', 'publisher_id'); // One-to-one relationship with User model (Publisher)
    }

    /**
     * Get the website associated with this tracking.
     */
    public function website()
    {
        return $this->hasOne(Website::class, 'id', 'website_id'); // One-to-one relationship with Website model
    }
}
