<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductEpc extends Model
{
    public $incrementing = false;

    public $timestamps = false;

    public $fillable = [
        'code'
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
