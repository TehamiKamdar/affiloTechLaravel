<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class State extends BaseModelWithoutUuids
{
    use SoftDeletes;

    // Define the dates for timestamps
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    // Define the fillable attributes
    protected $fillable = [
        'id',
        'country_id',
        'name',
        'status'
    ];

    // Define relationships

    // A state can have many cities
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    // A state belongs to a country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
