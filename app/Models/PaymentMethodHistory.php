<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethodHistory extends BaseModel
{
    // Constants for various payment statuses
    const PENDING = "pending";
    const PAID = "paid";

    use SoftDeletes;  // Use SoftDeletes for soft deleting records

    protected $table = "payment_method_histories";  // Table name

    protected $fillable = [
        "payment_history_id",
        "user_id",
        "website_id",
        "payment_frequency",
        "payment_threshold",
        "payment_method",
        "bank_location",
        "account_holder_name",
        "bank_account_number",
        "bank_code",
        "account_type",
        "paypal_country",
        "paypal_holder_name",
        "paypal_email",
        "payoneer_holder_name",
        "payoneer_email",
        "status"
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

    // Define relationships

    // Payment history relationship
    public function payment_history()
    {
        return $this->belongsTo(PaymentHistory::class, 'payment_history_id', 'id');
    }
}
