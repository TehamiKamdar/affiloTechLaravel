<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelTracking extends Model
{
    protected $table = "del_trackings";  // Table name
    protected $guarded = [];  // No attributes are guarded (all attributes are mass assignable)

    // Relationship with the Advertiser model
    public function advertiser()
    {
        return $this->hasOne(Advertiser::class, 'id', 'advertiser_id');
    }

    // Relationship with the AdvertiserApply model
    public function advertiserApply()
    {
        return $this->hasOne(AdvertiserPublisher::class, 'internal_advertiser_id', 'advertiser_id');
    }

    // Relationship with the Publisher model (User)
    public function publisher()
    {
        return $this->hasOne(User::class, 'id', 'publisher_id');
    }

    // Relationship with the Website model
    public function website()
    {
        return $this->hasOne(Website::class, 'id', 'website_id');
    }
}
