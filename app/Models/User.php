<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Scopes\OrderByCreateScope;
use App\Traits\PasswordResettable;
use App\Traits\PasswordHash;
use App\Traits\LastLogin;
use App\Traits\Picture;
use App\Traits\Search;
use Webpatser\Uuid\Uuid;
use Authy\AuthyApi;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes,
        PasswordResettable,
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
    }

    /**
     * Find user by phone number
     * @param  string $phone Phone number
     * @return User
     */
    public static function findByPhoneNumber($phone)
    {
        $matches = [];

        preg_match('/^(09|\+639)(\d{9})$/', $phone, $matches);

        $query = self::where('phone_number', $phone);

        if (count($matches) == 3) {
            $query
                ->orWhere('phone_number', '09' . $matches[2])
                ->orWhere('phone_number', '+63' . $matches[2]);
        }

        return $query->first();
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

    /**
     * Send SMS verification message
     * 
     * @return array|boolean
     */
    public function sendSmsVerification()
    {
        $authy = app(AuthyApi::class);

        if (!$this->phone_number)
            return false;

        return $authy->phoneVerificationStart($this->phone_number, '63', 'sms');
    }

    /**
     * Verify SMS code
     * 
     * @param  string $code Verification Code
     * @return boolean
     */
    public function verifySmsCode($code)
    {
        $authy = app(AuthyApi::class);

        if (!$this->phone_number)
            return false;

        $response = $authy->phoneVerificationCheck($this->phone_number, '63', $code);

        return $response->ok();
    }

    /**
     * Get 'email' attribute
     * 
     * @return string
     */
    public function getEmailAttribute()
    {
        return $this->email_address;
    }
}
