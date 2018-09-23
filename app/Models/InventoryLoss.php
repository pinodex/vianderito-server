<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\OrderByCreateScope;
use Webpatser\Uuid\Uuid;

class InventoryLoss extends Model
{
    public $incrementing = false;

    public $fillable = [
        'inventory_id',
        'units',
        'remarks'
    ];

    protected $casts = [
        'units' => 'integer'
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

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
