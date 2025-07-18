<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends BaseModel
{
    use SoftDeletes;

    protected $table = "coupons";

    protected $fillable = [
        'advertiser_id',
        'sid',
        'advertiser_name',
        'advertiser_status',
        'promotion_id',
        'type',
        'title',
        'description',
        'terms',
        'start_date',
        'end_date',
        'source',
        'url',
        'url_tracking',
        'date_added',
        'campaign',
        'code',
        'exclusive',
        'regions',
        'categories'
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    protected $casts = [
        'regions' => 'array',
        'categories' => 'array'
    ];

    public function advertiser_applies()
    {
        return $this->hasOne(AdvertiserPublisher::class, 'advertiser_id', 'internal_advertiser_id')
            ->where('publisher_id', auth()->user()->publisher_id)
            ->where('website_id', auth()->user()->active_website_id);
    }

    public function advertiser_applies_without_auth()
    {
        return $this->hasOne(AdvertiserPublisher::class, 'advertiser_id', 'internal_advertiser_id');
    }

    public function advertiser()
    {
        return $this->hasOne(Advertiser::class, 'id', 'internal_advertiser_id');
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)->with([$relation => $constraint]);
    }

    public function scopeWithAndWhereDoesntHave($query, $relation, $constraint)
    {
        return $query->whereDoesntHave($relation, $constraint)->with([$relation => $constraint]);
    }
}
