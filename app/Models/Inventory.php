<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\OrderByCreateScope;
use Webpatser\Uuid\Uuid;
use App\Traits\Search;

class Inventory extends Model
{
    use SoftDeletes,
        Search;

    public $incrementing = false;

    public $fillable = [
        'product_id',
        'eid',
        'quantity',
        'cost',
        'price',
        'batch_date',
        'expiration_date'
    ];

    protected $dates = [
        'deleted_at'
    ];

    protected $casts = [
        'cost' => 'float',
        'price' => 'float',
        'created_at' => 'string',
        'updated_at' => 'string'
    ];

    public $appends = [
        'is_expired',
        'is_near_expiration'
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
     * Add where not expired clause
     * 
     * @return Builder
     */
    public static function whereNotExpired()
    {
        return self::where('expiration_date', '>', date('Y-m-d'));
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function losses()
    {
        return $this->hasMany(InventoryLoss::class);
    }

    public function transactions()
    {
        return $this->belongsToMany(Transaction::class, 'transactions_inventories')
            ->withPivot('quantity');
    }

    /**
     * Get graph data by field
     * 
     * @param  string $field Column name
     * @return Collection|array
     */
    public static function getGraphByField($field) {
        if (static::count() == 0) {
            return [];
        }

        return static::all()->groupBy($field)->map(function ($group) {
            return $group->count();
        });
    }

    /**
     * Get computed stocks
     * 
     * @return int
     */
    public function getStocksAttribute()
    {
        $taken = 0;
        $stocks = $this->quantity;

        $this->losses->each(function (InventoryLoss $loss) use (&$taken) {
            $taken += $loss->units;
        });

        $this->transactions->each(function (Transaction $transaction) use (&$taken) {
            $transaction->purchases->each(function (Purchase $purchase) use (&$taken) {
                $purchase->products->each(function (PurchaseProduct $purchaseProduct) use (&$taken) {
                    if ($this->product_id == $purchaseProduct->product_id) {
                        $taken += $purchaseProduct->quantity;
                    }
                });
            });
        });

        $stocks -= $taken;

        return $stocks;
    }

    /**
     * total_loss attribute
     * 
     * @return int
     */
    public function getTotalLossAttribute()
    {
        $taken = 0;

        $this->losses->each(function (InventoryLoss $loss) use (&$taken) {
            $taken += $loss->units;
        });

        return $taken;
    }

    /**
     * total_loss_price attribute
     * 
     * @return double|int
     */
    public function getTotalLossPriceAttribute()
    {
        return $this->total_loss * $this->price;
    }

    /**
     * total_loss_cost attribute
     * 
     * @return double|int
     */
    public function getTotalLossCostAttribute()
    {
        return $this->total_loss * $this->cost;
    }

    /**
     * total_sale attribute
     * 
     * @return int
     */
    public function getTotalSaleAttribute()
    {
        $taken = 0;

        $this->transactions->each(function (Transaction $transaction) use (&$taken) {
            $transaction->purchases->each(function (Purchase $purchase) use (&$taken) {
                $purchase->products->each(function (PurchaseProduct $purchaseProduct) use (&$taken) {
                    if ($this->product_id == $purchaseProduct->product_id) {
                        $taken += $purchaseProduct->quantity;
                    }
                });
            });
        });

        return $taken;
    }

    /**
     * total_sale_price attribute
     * 
     * @return double|int
     */
    public function getTotalSalePriceAttribute()
    {
        return $this->total_sale * $this->price;
    }

    /**
     * total_sale_cost attribute
     * 
     * @return double|int
     */
    public function getTotalSaleCostAttribute()
    {
        return $this->total_sale * $this->cost;
    }

    /**
     * is_expired attribute
     * 
     * @return boolean
     */
    public function getIsExpiredAttribute()
    {
        return now()->gte($this->expiration_date);
    }

    /**
     * is_near_expired attribute
     * 
     * @return boolean
     */
    public function getIsNearExpirationAttribute()
    {
        return now()->diffInDays($this->expiration_date) <= 5;
    }
}
