<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\OrderByCreateScope;
use Webpatser\Uuid\Uuid;

class GatewayCustomer extends Model
{
    public $incrementing = false;

    public $fillable = [
        'customer_id',
        'token',
        'type',
        'last_four',
        'expiration_month',
        'expiration_year'
    ];

    public $appends = [
        'expiration'
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

    public function getExpirationAttribute()
    {
        return sprintf('%s/%s', $this->expiration_month, $this->expiration_year);
    }
}
