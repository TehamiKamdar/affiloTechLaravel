<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends BaseModel
{
    use SoftDeletes;  // Enables soft deletion for this model

    public $table = 'permissions';  // Table name

    /**
     * The attributes that are used for dates.
     *
     * @var array<string, string>
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];  // Date attributes

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',  // The title of the permission
    ];  // Mass assignable attributes

    /**
     * Define the relationship with the Role model.
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);  // Many-to-many relationship with the Role model
    }
}
