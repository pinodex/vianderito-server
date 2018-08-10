<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Scopes\OrderByCreateScope;
use App\Traits\PasswordHash;
use App\Traits\LastLogin;
use App\Traits\Picture;
use App\Traits\Search;
use Webpatser\Uuid\Uuid;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes,
        PasswordHash,
        LastLogin,
        Picture,
        Search;

    public $incrementing = false;

    public $fillable = [
        'name',
        'username',
        'email_address',
        'phone_number',
        'password',
        'is_verified'
    ];

    public $appends = [
        'picture'
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

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Set user verification status to true
     */
    public function verify()
    {
        $this->is_verified = true;

        $this->save();
    }
}
