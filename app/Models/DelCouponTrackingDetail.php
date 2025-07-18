<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelCouponTrackingDetail extends Model
{
    protected $table = "del_coupon_tracking_details";  // Table name
    protected $guarded = [];  // No attributes are guarded (all attributes are mass assignable)

    // Relationship with the Advertiser model
    public function advertiser()
    {
        return $this->belongsTo(Advertiser::class, "advertiser_id", "id");
    }
}
