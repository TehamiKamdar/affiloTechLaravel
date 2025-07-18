<?php
namespace App\Models;

class History extends BaseModel
{
    // Define the table associated with the model
    protected $table = 'histories';

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
        'queue',
        'landing_url',
        'sub_id',
        'key',
        'page',
        'offset',
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
