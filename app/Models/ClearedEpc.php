<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClearedEpc extends Model
{
    public $fillable = [
        'code'
    ];

    /**
     * Check EPCs for clearance
     * 
     * @param  array $epcs EPCs
     * @return mixed
     */
    public static function check($epcs)
    {
        $cleared = self::whereIn('code', $epcs)->get()->pluck('code');

        $data = collect($epcs)->map(function ($epc) use ($cleared) {
            $status = false;

            if ($cleared->contains($epc)) {
                $status = true;
            }

            return [
                'epc' => $epc,
                'status' => $status
            ];
        });

        return $data;
    }
}
