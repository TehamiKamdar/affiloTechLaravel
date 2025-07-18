<?php
namespace App\Models;

class EmailJob extends BaseModelWithoutUuids
{
    // Define the table associated with the model
    protected $table = 'email_jobs';

    // Define constants for job processing and status
    const IS_PROCESSING_NOT_START = 0;
    const IS_PROCESSING = 2;
    const IS_PROCESSING_COMPLETED = 1;
    const STATUS_COMPLETED = 0;
    const STATUS_FAILED = 2;
    const STATUS_ACTIVE = 1;

    // Define the date attributes
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'path',
        'date',
        'status',
        'is_processing',
        'payload',
        'error_code',
        'error_message'
    ];
}
