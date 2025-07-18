<?php
namespace App\Models;

class Mix extends BaseModel
{
    // Define constants for the categories
    const CATEGORY = "category";
    const PARTNER_TYPE = "partner_type";
    const PROMOTIONAL_METHOD = "promotional_method";

    // Database connection and table configuration
    protected $connection = "fetch_db";
    protected $table = "mixes";

    // Define the dates for timestamps
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'type',
        'external_id',
        'partner_id',
        'created_by',
        'updated_by',
        'source'
    ];
}

