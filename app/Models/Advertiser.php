<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Advertiser extends BaseModel
{
    use SoftDeletes;
    protected $connection = "fetch_db";
    const API = "api", MANUAL = "manual";
    const THIRD_PARTY = "third_party_advertiser", MANAGED_BY = "managed_by_the_affiliate", THIRD_PARTY_TEXT = "Third-Party Advertisers", MANAGED_BY_TEXT = "Managed by the Affiliate";
    const NOT_AVAILABLE = 0, AVAILABLE = 1;
    protected $table = "advertisers";
    protected $fillable = [
        "id",
        "advertiser_id",
        "api_advertiser_id",
        "user_id",
        "network_source_id",
        "sid",
        "company_name",
        "phone_number",
        "address",
        "country",
        "city",
        "state",
        "country_full_name",
        "name",
        "url",
        "custom_domain",
        "primary_regions",
        "currency_code",
        "average_payment_time",
        "validating_days",
        "epc",
        "click_through_url",
        "deeplink_enabled",
        "categories",
        "tags",
        "offer_type",
        "supported_regions",
        "logo",
        "fetch_logo_url",
        "is_fetchable_logo",
        "fetch_logo_error",
        "program_restirctions",
        "promotional_methods",
        "description",
        "short_description",
        "program_policies",
        "source",
        "type",
        "status",
        "commission",
        "commission_type",
        "go_to_cookie_lifetime",
        "exclusive",
        "is_request__process",
        "is_active",
        "is_available",
        "custom_domain"
    ];
    protected $dates = ["updated_at", "created_at", "deleted_at"];
    protected $casts = [
        "categories" => "array",
        "valid_domains" => "array",
        "primary_regions" => "array",
        "supported_regions" => "array",
        "promotional_methods" => "array",
        "program_restrictions" => "array",
        "country_full_name" => "array"
    ];

    public function commissions()
    {
        return $this->hasMany(Commission::class, "advertiser_id")->orderBy("created_at", "ASC");
    }

    public function validation_domains()
    {
        return $this->hasMany(ValidationDomain::class, "advertiser_id")->orderBy("created_at", "ASC");
    }

    public function getCountry()
    {
        return $this->hasOne(Country::class, "id", "country");
    }

    public function getState()
    {
        return $this->hasOne(State::class, "id", "state");
    }

    public function getCity()
    {
        return $this->hasOne(City::class, "id", "city");
    }

    public function advertiser_applies()
    {
        return $this->hasOne(AdvertiserPublisher::class, "advertiser_id", "id")
            ->where("publisher_id", auth()->user()->publisher_id)
            ->where("website_id", auth()->user()->active_website_id);
    }

    public function advertiser_applies_multi()
    {
        return $this->hasMany(AdvertiserPublisher::class, "advertiser_id", "id");
    }

    public function advertiser_applies_without_auth()
    {
        return $this->hasOne(AdvertiserPublisher::class, "advertiser_id", "id");
    }

    public function user()
    {
        return $this->hasOne(User::class, "id", "user_id");
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
