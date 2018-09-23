<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\OrderByCreateScope;
use Webpatser\Uuid\Uuid;
use App\Traits\Search;

class Inventory extends Model
{
    use SoftDeletes,
        Search;

    public $incrementing = false;

    public $fillable = [
        'product_id',
        'eid',
        'stocks',
        'critical_stocks',
        'cost',
        'price',
        'batch_date',
        'expiration_date'
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'cost' => 'float',
        'price' => 'float',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByCreateScope);

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });
    }

    /**
     * Add where not expired clause
     * 
     * @return Builder
     */
    public static function whereNotExpired()
    {
        return self::where('expiration_date', '>', date('Y-m-d'));
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
