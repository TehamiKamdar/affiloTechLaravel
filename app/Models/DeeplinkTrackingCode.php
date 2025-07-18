<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeeplinkTrackingCode extends Model
{
    protected $table = "deeplink_tracking_codes";

    protected $fillable = [
        "code",
        "digit",
    ];
}