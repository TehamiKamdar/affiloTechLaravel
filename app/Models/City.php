<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class City extends BaseModelWithoutUuids
{
    use SoftDeletes;

    // Define the fillable attributes
    protected $fillable = [
        'id', 
        'state_id', 
        'name', 
        'status'
    ];

    // Define the date attributes
    protected $dates = [
        'updated_at', 
        'created_at'
    ];

    /**
     * Get the state that owns the city.
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }
}
