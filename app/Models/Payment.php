<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\OrderByCreateScope;
use Webpatser\Uuid\Uuid;

class Payment extends Model
{
    public $incrementing = false;

    public $fillable = [
        'amount',
        'user_id',
        'gateway_id',
        'status'
    ];

    protected $casts = [
        'amount' => 'float'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
}
