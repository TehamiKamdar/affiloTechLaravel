<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Billing extends BaseModel
{
    use SoftDeletes;

    protected $table = "billings";

    protected $fillable = [
        "user_id",
        "name",
        "phone",
        "address",
        "zip_code",
        "country",
        "state",
        "city",
        "company_registration_no",
        "tax_vat_no"
    ];

    protected $dates = [
        "updated_at",
        "created_at",
        "deleted_at"
    ];

    public function fetchCountry()
    {
        return $this->belongsTo(Country::class, "country", "id");
    }

    public function fetchCity()
    {
        return $this->belongsTo(City::class, "city", "id");
    }

    public function fetchState()
    {
        return $this->belongsTo(State::class, "state", "id");
    }
}
