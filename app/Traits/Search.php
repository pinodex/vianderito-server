<?php

namespace App\Traits;

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
        return self::where(function ($builder) use ($query, $strict) {
            foreach ($query as $key => $value) {
                if ($value) {
                    if ($strict) {
                        $builder->where($key, 'LIKE', '%' . $value . '%');                        
                        
                        continue;
                    }

                    $builder->orWhere($key, 'LIKE', '%' . $value . '%');
                }
            }
        });
    }
}
