<?php
namespace App\Traits;

use Illuminate\Support\Str;

trait Uuids
{
    // Boot method to generate a UUID for the model's primary key
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate a UUID if the primary key is not set
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    // Override the incrementing method to return false (UUIDs are non-incrementing)
    public function getIncrementing()
    {
        return false;
    }

    // Override the key type to return 'string' for UUIDs
    public function getKeyType()
    {
        return 'string';
    }
}
