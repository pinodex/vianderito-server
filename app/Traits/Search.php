<?php

namespace App\Traits;

use DB;
use Illuminate\Database\Eloquent\Builder;

trait Search
{
    /**
     * Add where queries to builder
     * 
     * @param  array $query Search queries
     * @param  boolean $strict Strict matching
     * @return Builder
     */
    public static function search($query, $strict = false)
    {
        return self::where(function ($builder) use ($query) {
            foreach ($query as $key => $value) {
                if ($value) {
                    if ($key == 'fullname') {
                        self::applyFullNameSearch($builder, $value);

                        continue;
                    }

                    $builder->orWhere($key, 'LIKE', '%' . $value . '%');
                }
            }
        });
    }

    /**
     * Search models by IDs
     * @param  array $id Array of IDs
     * @return Builder
     */
    public static function searchByIds(array $id)
    {
        return self::whereIn('id', $id);
    }

    /**
     * Apply full text search for last_name, first_name, middle name columns
     *
     * @param Builder $builder Query builder
     * @param string $queryString Query string
     */
    private static function applyFullNameSearch(Builder $builder, $queryString)
    {
        $nameConcats = [
            "CONCAT(last_name, ' ', first_name, ' ', middle_name)",
            "CONCAT(last_name, ', ', first_name, ' ', middle_name)",
            "CONCAT(first_name, ' ', middle_name, ' ', last_name)",
            "CONCAT(first_name, ' ', last_name)"
        ];

        $builder->where(function (Builder $builder) use ($nameConcats, $queryString) {
            foreach ($nameConcats as $concat) {
                $builder->orWhere(DB::raw($concat), 'LIKE', "%{$queryString}%");
            }
        });
    }
}
