<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Scopes\OrderByCreateScope;
use Webpatser\Uuid\Uuid;

class Transaction extends Model
{
    use SoftDeletes;

    public $incrementing = false;

    public $fillable = [
        'user_id',
        'status'
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
     * Add inventory from product IDs
     * 
     * @param array $ids Product IDs
     */
    public function addInventoriesFromProductIds($data)
    {
        $data = collect($data);

        $ids = [];
        $quantites = [];
        $inventoryIds = [];

        $data->each(function ($entry) use (&$ids, &$quantites) {
            $ids[] = $entry['product_id'];

            $quantites[$entry['product_id']] = $entry['quantity'];
        });

        Product::whereIn('id', $ids)->get()
            ->each(function (Product $product) use (&$inventoryIds, $quantites) {
                $quantity = $quantites[$product->id];
                $inventory = $product->selectNextInventory($quantity);

                if ($inventory) {
                    $inventoryIds[] = $inventory->id;
                }
            });

        $this->inventories()->sync($inventoryIds);
    }

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'transactions_inventories');
    }
}
