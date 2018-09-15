<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\OrderByCreateScope;
use Webpatser\Uuid\Uuid;
use Carbon\Carbon;

class PasswordReset extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    public $fillable = [
        'token',
        'expires_at'
    ];

    protected $dates = [
        'expires_at',
        'deleted_at'
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

    public function hasExpired() {
        return $this->expires_at->lt(Carbon::now());
    }

    /**
     * Related model
     */
    public function entity()
    {
        return $this->morphTo();
    }
}
