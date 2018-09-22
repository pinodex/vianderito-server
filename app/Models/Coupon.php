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
        'validity_end'
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
