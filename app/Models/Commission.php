<?php
namespace App\Models;

class Commission extends BaseModel
{
    // Define the database connection and table
    protected $connection = 'fetch_db';
    protected $table = 'commissions';

    // Define the date attributes
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    // Define the fillable attributes
    protected $fillable = [
        'advertiser_id',
        'created_by',
        'updated_by',
        'date',
        'condition',
        'rate',
        'type',
        'info'
    ];
}
