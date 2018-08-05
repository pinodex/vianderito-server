<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Scopes\OrderByCreateScope;
use App\Traits\PasswordHash;
use App\Traits\LastLogin;
use Webpatser\Uuid\Uuid;

class User extends Authenticatable
{
    use SoftDeletes,
        PasswordHash,
        LastLogin;

    public $incrementing = false;

    public $fillable = [
        'name',
        'username',
        'email_address',
        'phone_number',
        'password'
    ];

    public $dates = [
        'last_login_at'
    ];

    public $hidden = [
        'password'
    ];

    public $casts = [
        'is_verified' => 'bool'
    ];

    /**
     * Boot function from laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope(new OrderByCreateScope);

        static::creating(function ($model) {
            $model->{$model->getKeyName()} = Uuid::generate()->string;
        });

        static::updating(function ($model) {
            if ($model->getOriginal('email_address') !=
                $model->getAttribute('email_address')) {
                
                $model->is_verified = false;
            }
        });
    }
}
