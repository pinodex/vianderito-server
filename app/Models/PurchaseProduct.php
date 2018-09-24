<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\OrderByCreateScope;
use Webpatser\Uuid\Uuid;

class PurchaseProduct extends Model
{
    public $incrementing = false;

    public $fillable = [
        'purchase_id',
        'product_id',
        'name',
        'upc',
        'price',
        'quantity',
        'subtotal'
    ];

    protected $casts = [
        'price' => 'float',
        'quantity' => 'integer',
        'subtotal' => 'float'
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

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->hasOne(Product::class);
    }
}
