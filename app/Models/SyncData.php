<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncData extends Model
{
    // Define the table name
    protected $table = 'sync_data';

    // Define the dates for timestamps
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'source',
        'type',
        'is_sync',
        'date'
    ];
}
