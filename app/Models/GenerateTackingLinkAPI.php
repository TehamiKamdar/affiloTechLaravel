<?php
namespace App\Models;

class GenerateTackingLinkAPI extends BaseModel
{
    protected $table = "generate_tacking_link_apis";  // Table name

    /**
     * The attributes that are used for dates.
     *
     * @var array<string, string>
     */
    protected $dates = [
        'updated_at',
        'created_at',
    ];  // Date attributes

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "advertiser_id",
        "publisher_id",
        "website_id",
        "u1",
        "click_through_url",
        "tracking_url_short",
        "tracking_url",
        "last_activity",
        "hits",
        "unique_visitor",
    ];  // Mass assignable attributes
}
