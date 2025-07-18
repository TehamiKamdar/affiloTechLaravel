<?php
namespace App\Models;

class FetchDailyData extends BaseModel
{
    // Define constants for statuses
    const STATUS_NOT_PROCESS = 1;
    const STATUS_ACTIVE = 0;
    const STATUS_IN_PROCESS = 2;
    const IN_PROCESS_ACTIVE = 1;
    const IN_PROCESS = 2;
    const IN_PROCESS_NOT = 0;

    // Define the table associated with the model
    protected $table = 'fetch_daily_data';

    // Define the date attributes
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'publisher_id',
        'website_id',
        'advertiser_id',
        'path',
        'process_date',
        'date',
        'payload',
        'status',
        'is_processing',
        'landing_url',
        'sub_id',
        'queue',
        'key',
        'page',
        'offerset',
        'limit',
        'sort',
        'start_date',
        'end_date',
        'error_code',
        'error_message',
        'source',
        'type'
    ];
}
