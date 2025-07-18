<?php
namespace App\Models;

class Clicks extends BaseModel
{
    protected $table = "clicks";

    protected $fillable = [
        "name",
        "publisher_id",
        "website_id",
        "advertiser_id",
        "path",
        "process_date",
        "date",
        "payload",
        "status",
        "is_processing",
        "queue",
        "landing_url",
        "sub_id",
        "key",
        "page",
        "offset",
        "limit",
        "sort",
        "start_date",
        "end_date",
        "error_code",
        "error_message",
        "source",
        "type",
    ];
}
