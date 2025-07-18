<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends BaseModelWithoutUuids
{
    use SoftDeletes;

    // Define the fillable attributes
    protected $fillable = [
        'id',
        'name',
        'status',
        'currency',
        'iso2'
    ];

    // Define the date attributes
    protected $dates = [
        'updated_at',
        'created_at'
    ];

    // Define the relationship with the State model
    public function states()
    {
        return $this->hasMany(State::class);
    }

    // Define the relationship with the City model through the State model
    public function cities()
    {
        return $this->hasManyThrough(City::class, State::class);
    }
}
