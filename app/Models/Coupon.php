<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\OrderByCreateScope;
use Webpatser\Uuid\Uuid;
use App\Traits\Search;

class Coupon extends Model
{
    use SoftDeletes,
        Search;

    public $incrementing = false;

    public $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_price',
        'discount_percentage',
        'discount_floor_price',
        'discount_ceiling_price',
        'validity_start',
        'validity_end',
        'quantity'
    ];

    protected $dates = [
        'deleted_at',
        'validity_start',
        'validity_end'
    ];

    protected $selectables = [
        'products',
        'suppliers',
        'inventories',
        'categories'
    ];

    public $appends = [
        'is_valid',
        'uses',
        'remainder'
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
     * Find coupon by code
     * 
     * @param  string $code Code
     * @return Coupon
     */
    public static function findByCode($code)
    {
        return static::where('code', $code)->first();
    }

    /**
     * Add where coupon date is valid clause
     * 
     * @return Builder
     */
    public static function whereCouponDateValid()
    {
        $now = now();

        return self::where('validity_start', '<', $now)->where('validity_end', '>', $now);
    }

    /**
     * Apply coupon substitutions to transaction
     * 
     * @param  Transaction $transaction Transaction model
     */
    public static function applyToTransaction(Transaction $transaction)
    {
        if (!$transaction->coupon) {
            return;
        }

        $transaction->inventories->map(function (Inventory $inventory) use ($transaction) {
            $less = $transaction->coupon->discount_price;

            if ($transaction->coupon->discount_type == 'percentage') {
                $less = $inventory->price - ($inventory->price * ($transaction->coupon->discount_percentage / 100));
            }

            if ($less < 0) {
                $less = 0;
            }

            $inventory->original_price = $inventory->price;
            $inventory->price -= $less;
            $inventory->is_discounted = true;            

            return $inventory;
        });
    }

    /**
     * Check transaction coupon eligibility
     * 
     * @param  Transaction $transaction Transaction model
     * @return 
     */
    public function isTransactionEligible(Transaction $transaction)
    {
        if (!$this->is_valid)
            return false;

        if ($this->product_ids->isNotEmpty()) {
            foreach ($transaction->involved_entity_ids['products'] as $id) {
                if (!$this->product_ids->contains($id)) {
                    return false;
                }
            }
        }

        if ($this->inventory_ids->isNotEmpty()) {
            foreach ($transaction->involved_entity_ids['inventories'] as $id) {
                if (!$this->inventory_ids->contains($id)) {
                    return false;
                }
            }
        }

        if ($this->supplier_ids->isNotEmpty()) {
            foreach ($transaction->involved_entity_ids['suppliers'] as $id) {
                if (!$this->supplier_ids->contains($id)) {
                    return false;
                }
            }
        }

        if ($this->category_ids->isNotEmpty()) {
            $hasInvalidOne = true;

            foreach ($transaction->involved_entity_ids['categories'] as $id) {
                if (!$this->category_ids->contains($id)) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Sync coupon eligibility selections
     * @param  array $selections Array of selections
     */
    public function syncSelections($selections) {
        foreach ($selections as $selection => $ids) {
            if (!in_array($selection, $this->selectables)) {
                continue;
            }

            $this->{$selection}()->sync($ids);
        }
    }

    /**
     * Get selections array attribute
     * 
     * @return array
     */
    public function getSelectionsAttribute()
    {
        $selections = [];

        foreach ($this->selectables as $selectable) {
            $selections[$selectable] = $this->{$selectable}->pluck('id');
        }

        return $selections;
    }

    /**
     * Check if coupon is valid
     * 
     * @return boolean
     */
    public function getIsValidAttribute()
    {
        return now()->gte($this->validity_start) && now()->lt($this->validity_end);
    }

    /**
     * Get selected product IDS
     * 
     * @return Collection
     */
    public function getProductIdsAttribute()
    {
        return $this->products->pluck('id');
    }

    /**
     * Get selected supplier IDS
     * 
     * @return Collection
     */
    public function getSupplierIdsAttribute()
    {
        return $this->suppliers->pluck('id');
    }

    /**
     * Get selected inventories IDS
     * 
     * @return Collection
     */
    public function getInventoryIdsAttribute()
    {
        return $this->inventories->pluck('id');
    }

    /**
     * Get selected category IDS
     * 
     * @return Collection
     */
    public function getCategoryIdsAttribute()
    {
        return $this->categories->pluck('id');
    }

    /**
     * uses attribute
     * 
     * @return int
     */
    public function getUsesAttribute()
    {
        return $this->purchases()->count();
    }

    public function getRemainderAttribute()
    {
        return $this->quantity - $this->uses;
    }

    /**
     * Purchase relation
     * 
     * @return HasMany
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    /**
     * Product relation
     * 
     * @return BelongsToMany
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'coupons_products');
    }

    /**
     * Supplier relation
     * 
     * @return BelongsToMany
     */
    public function suppliers()
    {
        return $this->belongsToMany(Supplier::class, 'coupons_suppliers');
    }

    /**
     * Inventory relation
     * 
     * @return BelongsToMany
     */
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'coupons_inventories');
    }

    /**
     * Category relation
     * 
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'coupons_categories');
    }
}
