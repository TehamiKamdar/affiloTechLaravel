<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrackingUrl extends Model
{
    // Constants for the model
    const DEEP_LINK = 'deep_link';
    const LINK = 'link';

    // Define the table name
    protected $table = 'tracking_urls';

    // Define the fillable attributes
    protected $fillable = [
        'advertiser_id',
        'publisher_id',
        'website_id',
        'sub_id',
        'original_url',
        'short_url',
        'url',
        'type',
        'opens'
    ];
}
