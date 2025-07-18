<?php
namespace App\Models;

class GenerateLink extends BaseModelWithoutUuids
{
    protected $table = "generate_links";  // Table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "name",
        "publisher_id",
        "website_id",
        "advertiser_id",
        "sub_id",
        "path",
        "date",
        "payload",
        "status",
        "is_processing",
        "queue",
        "landing_url",
        "error_code",
        "error_message"
    ];  // Mass assignable attributes
}
