<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Paypal extends BaseModel
{
    use SoftDeletes;  // Use SoftDeletes for soft deletion of records

    protected $table = "paypals";  // Table name

    protected $fillable = [
        "user_id",
        "name",
        "address",
        "username",
        "country",
        "frequency",
        "threshold",
        "tax_id",
        "tax_form"
    ];  // Mass assignable attributes

    /**
     * The attributes that are used for dates.
     *
     * @var array<string, string>
     */
    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];  // Date attributes
}
