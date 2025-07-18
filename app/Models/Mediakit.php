<?php
namespace App\Models;

class Mediakit extends BaseModel
{
    // Define the fillable attributes
    protected $fillable = [
        'user_id',
        'name',
        'image',
        'size'
    ];

    // Define the relationship with the User model
    public function users()
    {
        return $this->hasMany(User::class, 'user_id', 'publisher_id');
    }
}
