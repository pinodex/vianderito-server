<?php

namespace App\Models;

use Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\OrderByCreateScope;
use App\Traits\Picture;
use App\Traits\Search;
use Webpatser\Uuid\Uuid;

class Product extends Model
{
    use SoftDeletes,
        Picture,
        Search;

    public $incrementing = false;

    public $fillable = [
        'supplier_id',
        'category_id',
        'upc',
        'name',
        'floor',
        'ceiling',
        'description'
    ];

    public $appends = [
        'picture'
    ];

    protected $casts = [
        'floor' => 'integer',
        'ceiling' => 'integer'
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
     * Get products by EPC
     * 
     * @param  array $codes Product EPCs
     * @return array
     */
    public static function getProductsByEpcs($codes)
    {
        $epc = ProductEpc::with('product', 'product.frontInventory')
            ->whereIn('code', $codes)
            ->get();

        return $epc;
    }

    /**
     * Select next valid inventory
     * 
     * @return Inventory
     */
    public function selectNextInventory($quantity = 1)
    {
        return $this->inventories->sortBy('batch_date')
            ->first(function (Inventory $inventory, $key) use ($quantity) {
                return now()->lessThan($inventory->expiration_date) &&
                    $inventory->stocks >= $quantity;
                });
    }

    protected function getImageDimensions()
    {
        return [1280, 720];
    }

    protected function getDefaultImage()
    {
        return '/assets/img/generic-product-image.png';
    }

    protected function getDefaultThumbnail()
    {
        return '/assets/img/generic-product-thumb.png';
    }

    /**
     * Sync product to EPCs
     * 
     * @param  array $epcs Array of EPC codes
     */
    public function syncEpcs($epcs)
    {
        $models = [];

        foreach ($epcs as $epc) {
            $model = new ProductEpc;
            $model->code = $epc['code'];
            
            $models[] = $model;
        }

        $this->epcs()->delete();
        $this->epcs()->saveMany($models);
    }

    /**
     * Get product losses
     * 
     * @return InventoryLoss[]
     */
    public function getLosses()
    {
        $inventoryIds = $this->inventories->pluck('id');

        return InventoryLoss::with('inventory')
            ->whereIn('inventory_id', $inventoryIds)
            ->get();
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    public function epcs()
    {
        return $this->hasMany(ProductEpc::class);
    }

    public function frontInventory()
    {
        $frontInventory = $this->hasOne(Inventory::class)
            ->orderBy('batch_date', 'ASC')
            ->where('expiration_date', '>', date('Y-m-d'));

        return $frontInventory;
    }
}
