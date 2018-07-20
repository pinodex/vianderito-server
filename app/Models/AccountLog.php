<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Agent\Agent;

class AccountLog extends Model
{
    public $table = 'accounts_logs';

    public $fillable = [
        'account_id',
        'action',
        'params',
        'ip_address',
        'user_agent'
    ];

    public $appends = [
        'browser',
        'description'
    ];

    /**
     * @var \Jenssegers\Agent\Agent
     */
    protected $agent;

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('orderByCreatedAt', function (Builder $builder) {
            $builder->orderBy('created_at', 'DESC');
        });
    }

    /**
     * Get Agent instance for the recorded user agent
     * 
     * @return \Jenssegers\Agent\Agent
     */
    public function getAgent()
    {
        if ($this->agent !== null) {
            return $this->agent;
        }

        $this->agent = new Agent();
        $this->agent->setUserAgent($this->user_agent);

        return $this->agent;
    }

    /**
     * Get browser user agent friendly name
     * 
     * @return string
     */
    public function getBrowserAttribute()
    {
        $agent = $this->getAgent();

        return sprintf('%s (%s)', $agent->browser(), $agent->platform());
    }

    /**
     * Get action description
     * 
     * @return string
     */
    public function getDescriptionAttribute()
    {
        return __('action.' . $this->action, $this->params);
    }

    public function getParamsAttribute($value)
    {
        if ($value == null) {
            return [];
        }

        return json_decode($value, true);
    }

    public function setParamsAttribute(array $value)
    {
        $this->attributes['params'] = json_encode($value);
    }
}
