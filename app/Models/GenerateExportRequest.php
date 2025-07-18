<?php
namespace App\Models;

class GenerateExportRequest extends BaseModel
{
    // Define the table associated with the model
    protected $table = 'generate_export_requests';

    // Define the date attributes
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    // Define the fillable attributes
    protected $fillable = [
        'publisher_id',
        'website_id',
        'name',
        'path',
        'processes_date',
        'date',
        'status',
        'is_processing',
        'queue',
        'error_code',
        'source',
        'format',
        'type',
        'error_message',
        'payload'
    ];
}
