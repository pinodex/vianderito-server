<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Scopes\OrderByCreateScope;
use Webpatser\Uuid\Uuid;

class Purchase extends Model
{
    public $incrementing = false;

    public $fillable = [
        'user_id',
        'amount'
    ];

    public $with = [
        'products'
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

    /**
     * Get sales graph
     * 
     * @return array
     */
    public static function getSalesGraph($view, $date)
    {
        $startDate = now()->parse($date);
        $endDate = now()->parse($date);
        
        $data = [];
        $days = 0;
        $format = 'm-d';

        if ($view == 'yearly') {
            $startDate->startOfYear();
            $endDate->endOfYear();

            $format = 'M';
            $days = 12;
        }

        if ($view == 'monthly') {
            $startDate->startOfMonth();
            $endDate->endOfMonth();
            
            $format = 'M d';
            $days = $startDate->diffInDays($endDate) + 1;
        }

        if ($view == 'weekly') {
            $endDate = $startDate->copy()->addDays(6);
            $format = 'm d l';
            $days = $startDate->diffInDays($endDate) + 1;
        }

        for ($i = 0; $i < $days; $i++) {
            $date = $startDate->copy();

            if ($view == 'monthly' || $view == 'weekly') {
                $date->addDays($i);
            }

            if ($view == 'yearly') {
                $date->addMonths($i);
            }

            $date = $date->format($format);

            $data[$date] = 0;
        }

        static::whereBetween('created_at', [$startDate, $endDate])
            ->each(function (Purchase $purchase) use (&$data, $view, $format) {
                $date = $purchase->created_at->format($format);

                $data[$date] += 1;
            });

        return $data;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }

    public function products()
    {
        return $this->hasMany(PurchaseProduct::class);
    }
}
