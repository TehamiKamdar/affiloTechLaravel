<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GenerateTrackingCode extends Model
{
    protected $table = "generate_tracking_codes";  // Table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "code",
        "digit"
    ];  // Mass assignable attributes
}
