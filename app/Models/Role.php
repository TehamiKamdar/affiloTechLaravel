<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    use SoftDeletes;  // Enable soft deletes

    // Role types
    const SUPER_ADMIN_ROLE = "Super Admin";
    const ADMIN_ROLE = "Admin";
    const STAFF_ROLE = "Staff";
    const ADVERTISER_ROLE = "Advertiser";
    const PUBLISHER_ROLE = "Publisher";

    // Table associated with the model
    public $table = 'roles';

    /**
     * The attributes that are used for dates.
     *
     * @var array<string, string>
     */
    protected $dates = [
        'created_at',  // Timestamp for creation
        'updated_at',  // Timestamp for updates
        'deleted_at',  // Timestamp for soft deletion
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',       // The role's title
        'created_at',  // Timestamp for creation
        'updated_at',  // Timestamp for updates
        'deleted_at',  // Timestamp for soft deletion
    ];

    /**
     * Get the users associated with this role.
     */
    public function users()
    {
        return $this->belongsToMany(User::class);  // Many-to-many relationship with User model
    }

    /**
     * Get the permissions associated with this role.
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);  // Many-to-many relationship with Permission model
    }
}
