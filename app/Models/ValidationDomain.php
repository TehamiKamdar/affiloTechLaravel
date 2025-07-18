<?php
namespace App\Models;

class ValidationDomain extends BaseModel
{
    // The table associated with the model
    protected $table = "validation_domains";

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "advertiser_id", // The ID of the advertiser associated with the domain
        "name",          // The name of the validation domain
        "created_by",    // The user or entity that created the validation domain
    ];

    /**
     * The attributes that are used for dates.
     *
     * @var array<string, string>
     */
    protected $dates = [
        'updated_at',    // The date when the record was last updated
        'created_at',    // The date when the record was created
    ];
}
