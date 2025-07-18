<?php
namespace App\Models;

class ExportFiles extends BaseModel
{
    // Define the table associated with the model
    protected $table = 'export_files';

    // Define the fillable attributes
    protected $fillable = [
        'publisher_id',
        'website_id',
        'name',
        'path'
    ];

    // Define the date attributes
    protected $dates = [
        'expired_at',
        'updated_at',
        'created_at'
    ];
}
