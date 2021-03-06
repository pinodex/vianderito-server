<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Scopes\OrderByCreateScope;
use App\Traits\PasswordResettable;
use App\Traits\PasswordHash;
use App\Traits\LastLogin;
use App\Traits\Picture;
use App\Traits\Search;
use Webpatser\Uuid\Uuid;

class Account extends Authenticatable
{
    use SoftDeletes,
        PasswordResettable,
        PasswordHash,
        LastLogin,
        Picture,
        Search;

    public $incrementing = false;

    public $fillable = [
        'department_id',
        'first_name',
        'middle_name',
        'last_name',
        'username',
        'email'
    ];

    public $with = [
        'department'
    ];

    public $appends = [
        'name',
        'picture'
    ];

    public $hidden = [
        'password'
    ];

    public $casts = [
        'require_password_change' => 'bool',
        'is_enabled' => 'bool'
    ];

    protected $dates = [
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

    /**
     * Generate random password
     *
     * @param $requireChange boolean Require password change
     * 
     * @return string
     */
    public function generatePassword($requireChange = true)
    {
        $password = str_random(8);

        $this->password = $password;
        $this->require_password_change = $requireChange;

        return $password;
    }

    /**
     * Log action to account
     * 
     * @param  string $action Action key
     * @param  array  $params Action parameters
     * @param  HttpRequest $request Request object
     */
    public function log($action, $params = [], HttpRequest $request = null)
    {
        if ($request == null) {
            $request = request();
        }

        $log = new AccountLog();

        $log->fill([
            'action'        => $action,
            'params'        => $params,
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->header('User-Agent')
        ]);

        $this->logs()->save($log);
    }

    /**
     * Check if account has permission of id
     * 
     * @param  [type] $permission Permission id
     * @return boolean
     */
    public function canDo($permission)
    {
        if (is_array($permission)) {
            return $this->department->permissions()->whereIn('id', $permission)->first() != null;
        }

        return $this->department->permissions()->where('id', $permission)->first() != null;
    }

    /**
     * Get concatenated first name and last name
     * 
     * @return string
     */
    public function getNameAttribute()
    {
        return sprintf('%s %s', $this->first_name, $this->last_name);
    }

    /**
     * Get account department
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    
    /**
     * Get account logs 
     */
    public function logs()
    {
        return $this->hasMany(AccountLog::class);
    }
}
