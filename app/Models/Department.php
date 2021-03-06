<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\OrderByCreateScope;
use App\Traits\Search;
use Webpatser\Uuid\Uuid;

class Department extends Model
{
    use SoftDeletes,
        Search;

    public $incrementing = false;
    
    public $fillable = [
        'name',
        'description'
    ];

    public $with = [
        'permissions'
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

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'departments_permissions');
    }

    public function accounts()
    {
        return $this->hasMany(Account::class);
    }
}
