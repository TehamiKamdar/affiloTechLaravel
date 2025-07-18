<?php
namespace App\Models;

class RemoveAdvertiserIDz extends BaseModel
{
    protected $table = "remove_advertiser_i_dzs";  // Table name

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'advertiser_id',  // The advertiser ID
        'source',         // The source associated with the advertiser
    ];  // Mass assignable attributes
}
