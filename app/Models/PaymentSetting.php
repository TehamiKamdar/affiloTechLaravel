<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentSetting extends BaseModel
{
    use SoftDeletes;

    // Database table configuration
    protected $table = "payment_settings";

    // Define the fillable attributes
    protected $fillable = [
        'user_id',
        'website_id',
        'payment_frequency',
        'payment_threshold',
        'payment_method',
        'bank_location',
        'account_holder_name',
        'bank_account_number',
        'bank_code',
        'account_type',
        'payment_country',
        'payment_email',
        'payment_email_name',
        'payment_phone_number',
        'email',
    "paypal_country",
    "paypal_holder_name",
    "paypal_email",
    "payoneer_holder_name",
    "payoneer_email",
    ];

    // Define the dates for timestamps
    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at'
    ];

    // Define the relationship to the User model
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
