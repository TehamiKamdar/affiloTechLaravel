<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends BaseModel
{
    use SoftDeletes;

    // Define the table name
    protected $table = 'companies';

    // Define the fillable attributes
    protected $fillable = [
        'user_id',
        'company_name',
        'contact_name',
        'legal_entity_type',
        'phone_number',
        'address',
        'address_2',
        'city',
        'state',
        'country',
        'zip_code'
    ];

    // Define the date attributes
    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    // Define the relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Define the relationship with the Country model
    public function getCountry()
    {
        return $this->belongsTo(Country::class, 'country');
    }

    // Get a list of legal entity types
    public static function getLegalEntityList()
    {
        return [
            'Individual / Sole Proprietorship',
            'Partners',
            'Corporation',
            'LLC / LCP',
            'Others'
        ];
    }
}
