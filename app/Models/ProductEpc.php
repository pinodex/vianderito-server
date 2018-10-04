<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductEpc extends Model
{
    const STATUS_OPEN = 'open';

    const STATUS_CLEARED = 'cleared';

    const STATUS_HOLD = 'hold';

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
