<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Setting extends BaseModel
{
    use HasFactory;

    // Define the table associated with the model
    public $table = "settings";

    // Define the dates for timestamps
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    // Define the fillable attributes
    protected $fillable = [
        'key',
        'value',
        'is_informed'
    ];
}
