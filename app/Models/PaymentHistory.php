<?php
namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentHistory extends BaseModel
{
    // Constants for various payment statuses
    const PENDING = "pending";
    const PAID = "paid";
    const PENDING_TO_PAY = 'pending-to-pay';
    const PAID_TO_PUBLISHER = 'paid-to-publisher';
    const RELEASE_PAYMENT = 'release-payment';
    const PAYMENT_HISTORY = 'payment-history';
    const NO_PUBLISHER_PAYMENT = 'no-publisher-payment';
    const INVOICE_NEW = 1;
    const INVOICE_OLD = 0;

    use SoftDeletes;  // Use SoftDeletes for soft deleting records

    protected $table = "payment_histories";  // Table name

    protected $fillable = [
        "transaction_idz",
        "approver_id",
        "publisher_id",
        "website_id",
        "payment_id",
        "invoice_id",
        "amount",
        "commission_amount",
        "lc_commission_amount",
        "commission_percentage",
        "status",
        "is_new_invoice",
        "is_matched",
        "paid_date",
        "description",
        "transaction_id",
        "converted_amount"
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

    protected $casts = [
        'transaction_idz' => 'array'
    ];  // Cast transaction_idz to an array

    // Define relationships

    // User relationship (belongs to publisher)
    public function user()
    {
        return $this->belongsTo(User::class, 'publisher_id', 'id');
    }

    // Payment relationship
    public function payment()
    {
        return $this->belongsTo(PaymentSetting::class, 'publisher_id', 'user_id');
    }

    // Payment method history relationship
    public function payment_method()
    {
        return $this->belongsTo(PaymentMethodHistory::class, 'id', 'payment_history_id');
    }

    // Country relationship for PayPal country
    public function fetchCountry()
    {
        return $this->belongsTo(Country::class, 'paypal_country', 'id');
    }

    // Bank location relationship
    public function fetchBankLocation()
    {
        return $this->belongsTo(Country::class, 'bank_location', 'id');
    }

    // Scope to fetch payment history by publisher
    public function scopeFetchPublisher($query, $value)
    {
        return $query->where('payment_histories.publisher_id', $value->id)
                     ->where('payment_histories.website_id', $value->active_website_id);
    }
}
