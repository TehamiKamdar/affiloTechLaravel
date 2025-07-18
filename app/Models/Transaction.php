<?php
namespace App\Models;

class Transaction extends BaseModel
{
    // Database connection name
    protected $connection = 'fetch_db';

    // Constants for various statuses
    const STATUS_APPROVED = 'approved';
    const STATUS_APPROVED_STALLED = 'approved_but_stalled';
    const STATUS_PENDING = 'pending';
    const STATUS_HOLD = 'hold';
    const STATUS_PENDING_PAID = 'pending_paid';
    const STATUS_PAID = 'paid';
    const STATUS_DECLINED = 'declined';
    const STATUS_DELETED = 'deleted';

    // Payment status constants
    const PAYMENT_STATUS_NOT_APPROVED = 0;
    const PAYMENT_STATUS_CONFIRM = 1;
    const PAYMENT_STATUS_REJECT = 2;
    const PAYMENT_STATUS_RELEASE = 3;
    const PAYMENT_STATUS_RELEASE_PAYMENT = 4;

    // Define the table name
    protected $table = 'transactions';

    // Define the fillable attributes
    protected $fillable = [
    'id',
    'internal_advertiser_id',
    'website_id',
    'external_advertiser_id',
    'advertiser_id',
    'transaction_id',
    'publisher_id',
    'commission_status',
    'payment_status',
    'received_commission_status',
    'received_commission_amount',
    'received_commission_amount_currency',
    'received_sale_amount',
    'is_converted',
    'commission_amount',
    'before_percentage_commission',
    'last_commission',
    'last_sales_amount',
    'tmp_commission_amount',
    'commission_amount_currency',
    'sale_amount',
    'tmp_sale_amount',
    'sale_amount_currency',
    'commission_sharing_publisher_id',
    'commission_sharing_selected_rate_publisher_id',
    'payment_id',
    'internal_payment_id',
    'transaction_query_id',
    'advertiser_name',
    'campaign_name',
    'site_name',
    'customer_country',
    'click_date',
    'transaction_date',
    'validation_date',
    'commission_type',
    'voucher_code',
    'lapse_time',
    'old_sale_amount',
    'old_commission_amount',
    'click_device',
    'transaction_device',
    'advertiser_country',
    'order_ref',
    'paid_to_publisher',
    'tracked_currency_amount',
    'tracked_currency_currency',
    'original_sale_amount',
    'ip_hash',
    'source',
    'sub_id',
    'url',
    'publisher_url',
    'amended_reason',
    'decline_reason',
    'customer_acquisition',
    'custom_parameters',
    'transaction_parts',
    'click_refs',
    'created_at',
    'updated_at'
];


    // Define the casts for the attributes
    protected $casts = [
        'custom_parameters' => 'array',
        'transaction_parts' => 'array',
        'click_refs' => 'array'
    ];

    // Scope for fetching publisher transactions
    public function scopeFetchPublisher($query, $value)
    {
        return $query->where('transactions.publisher_id', $value->id)
            ->where('transactions.website_id', $value->active_website_id);
    }

    // Relationships
    public function advertiser()
    {
        return $this->hasOne(Advertiser::class, 'id', 'external_advertiser_id');
    }

    public function publisher()
    {
        return $this->hasOne(User::class, 'id', 'publisher_id');
    }

    public function website()
    {
        return $this->hasOne(Website::class, 'id', 'website_id');
    }

    public function tracking()
    {
        return $this->hasOne(Tracking::class, 'advertiser_id', 'internal_advertiser_id')
            ->where('publisher_id', auth()->user()->id);
    }

    public function tracking_details()
    {
        return $this->hasOne(TrackingDetail::class, 'advertiser_id', 'internal_advertiser_id')
            ->where('publisher_id', auth()->user()->id);
    }

    public function scopeWithAndWhereHas($query, $relation, $constraint)
    {
        return $query->whereHas($relation, $constraint)->with([$relation => $constraint]);
    }
}
