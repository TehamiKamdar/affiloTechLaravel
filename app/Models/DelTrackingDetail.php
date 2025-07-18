<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DelTrackingDetail extends Model
{
    protected $table = "del_tracking_details";  // Table name
    protected $guarded = [];  // No attributes are guarded (all attributes are mass assignable)

    // Relationship with the Advertiser model
    public function advertiser()
    {
        return $this->hasOne(Advertiser::class, "id", "advertiser_id");
    }
}
