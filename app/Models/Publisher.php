<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Publisher extends BaseModel
{
    use SoftDeletes;

    // Database table configuration
    protected $table = "publishers";

    // Define the fillable attributes
    protected $fillable = [
        'user_id',
        'user_name',
        'language',
        'customer_reach',
        'gender',
        'dob',
        'location_country',
        'location_state',
        'location_city',
        'location_address_1',
        'location_address_2',
        'zip_code',
        'intro',
        'image'
    ];

    // Define the dates for timestamps
    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    // Define the cast types for specific attributes
    protected $casts = [
        'categories' => 'array',
        'partner_types' => 'array'
    ];

    // Get available languages for publishers
    public static function getLanguages()
    {
        return [
            "English", "Chinese", "Hindi", "Spanish", "Arabic", "Bengali", "Portuguese", 
            "Russian", "Punjabi", "Japanese", "Javanese", "Telugu", "Marathi", "French", 
            "German", "Tamil", "Urdu", "Vietnamese", "Korean", "Turkish", "Gujarati", 
            "Italian", "Hausa", "Malay", "Kannada", "Pashto", "Yoruba", "Persian", "Oriyya"
        ];
    }

    // Get legal entities associated with publishers
    public static function getLegalEntity()
    {
        return [
            [
                'name' => 'Individual/Sole Proprietorship',
                'value' => 'individual'
            ],
            [
                'name' => 'Partners',
                'value' => 'partners'
            ],
            [
                'name' => 'Corporation',
                'value' => 'corporation'
            ],
            [
                'name' => 'LLC/LLP',
                'value' => 'llc'
            ],
            [
                'name' => 'Other',
                'value' => 'other'
            ]
        ];
    }
}
