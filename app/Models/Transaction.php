<?php

namespace App\Models;

use DB;
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

        $data->each(function ($entry) use (&$ids, &$quantites) {
            $ids[] = $entry['product_id'];

            $quantites[$entry['product_id']] = $entry['quantity'];
        });

        $this->inventories()->sync([]);

        Product::whereIn('id', $ids)->get()
            ->each(function (Product $product) use ($quantites) {
                $quantity = $quantites[$product->id];
                $inventory = $product->selectNextInventory($quantity);

                if ($inventory) {
                    $this->inventories()->attach($inventory->id, [
                        'quantity' => $quantity
                    ]);
                }
            });
    }

    /**
     * Move transaction data to purchase
     */
    public function moveToPurchases(User $user)
    {
        $purchase = null;

        DB::transaction(function () use (&$purchase, $user) {
            $purchase = Purchase::create();
            
            $purchase->user()->associate($user);
            $purchase->transaction()->associate($this);

            $amount = 0;

            $this->inventories->each(function (Inventory $inventory) use ($purchase, &$amount) {
                $purchaseProduct = new PurchaseProduct();

                $purchaseProduct->purchase()->associate($purchase);
                
                $purchaseProduct->product_id = $inventory->product->id;
                $purchaseProduct->name = $inventory->product->name;
                $purchaseProduct->upc = $inventory->product->upc;
                $purchaseProduct->price = $inventory->price;
                $purchaseProduct->quantity = $inventory->pivot->quantity;
                $purchaseProduct->subtotal = $inventory->price * $inventory->pivot->quantity;;

                $amount += $purchaseProduct->subtotal;

                $purchaseProduct->save();
            });

            $purchase->amount = $amount;
            $purchase->save();

            $this->status = 'complete';
            $this->save();
        });

        return $purchase;
    }

    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'transactions_inventories')
            ->withPivot('quantity');
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}