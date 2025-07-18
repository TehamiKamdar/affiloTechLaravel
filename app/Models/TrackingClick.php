<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class TrackingClick extends BaseModel
{
    protected $table = 'tracking_clicks';

    protected $fillable = [
        "publisher_id",
        "advertiser_id",
        "website_id",
        "link_type",
        "link_id",
        "created_year",
        "date",
        "total_clicks",
    ];

    public function advertiser()
    {
        return $this->hasOne(Advertiser::class, 'id', 'advertiser_id');
    }
}
