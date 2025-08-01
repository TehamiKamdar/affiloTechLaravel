<?php
namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use Uuids;

    /**
     * Get the SQL query with bindings.
     *
     * @param  \Illuminate\Database\Query\Builder  $query
     * @return string
     */
    public static function getSqlWithBindings($query)
    {
        return vsprintf(
            str_replace("?", "%s", $query->toSql()),
            collect($query->getBindings())->map(function ($binding) {
                return is_numeric($binding) ? $binding : "'{$binding}'";
            })->toArray()
        );
    }
}
