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

    const STATUS_PENDING = 'pending';

    const STATUS_LOCKED = 'locked';

    const STATUS_COMPLETE = 'complete';

    public $incrementing = false;

    public $fillable = [
        'user_id',
        'status'
    ];

    public $appends = [
        'total',
        'original_total',
        'is_discounted'
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

        $ids = $data->pluck('product_id');

        $quantites = $data->keyBy('product_id')->map(function ($entry) {
            return $entry['quantity'];
        });

        $items = $this->inventories->keyBy('id')->map(function ($entry) {
            return ['quantity' => $entry->pivot->quantity];
        });

        Product::whereIn('id', $ids)->get()
            ->each(function (Product $product) use ($quantites, &$items) {
                $quantity = $quantites[$product->id];
                $inventory = $product->selectNextInventory($quantity);

                if ($inventory) {
                    $items[$inventory->id] = ['quantity' => $quantity];
                }
            });

        $this->inventories()->sync($items->all());
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

            if ($this->coupon) {
                $purchase->coupon()->associate($this->coupon);
            }

            $this->inventories->each(function (Inventory $inventory) use ($purchase) {
                $purchaseProduct = new PurchaseProduct();

                $purchaseProduct->purchase()->associate($purchase);
                
                $purchaseProduct->product_id = $inventory->product->id;
                $purchaseProduct->name = $inventory->product->name;
                $purchaseProduct->upc = $inventory->product->upc;
                $purchaseProduct->price = $inventory->price;
                $purchaseProduct->quantity = $inventory->pivot->quantity;
                $purchaseProduct->subtotal = $inventory->price * $inventory->pivot->quantity;;

                $purchaseProduct->save();
            });

            $purchase->amount = $this->original_total;
            $purchase->discounted_amount = $this->total;
            $purchase->save();

            $this->status = self::STATUS_COMPLETE;
            $this->save();
        });

        return $purchase;
    }

    /**
     * Lock transaction
     */
    public function lockTransaction(User $user)
    {
        $this->status = self::STATUS_LOCKED;
        
        $this->user()->associate($user);

        $this->save();
    }

    /**
     * Get entities involved in this transaction entry
     * 
     * @return array
     */
    public function getInvolvedEntityIdsAttribute()
    {
        $ids = collect([
            'inventories' => collect([]),
            'products' => collect([]),
            'categories' => collect([]),
            'suppliers' => collect([])
        ]);

        $this->inventories->each(function (Inventory $inventory) use (&$ids) {
            $ids['inventories'][] = $inventory->id;

            if ($inventory->product) {
                $ids['products'][] = $inventory->product->id;

                if ($inventory->product->category) {
                    $ids['categories'][] = $inventory->product->category->id;
                }

                if ($inventory->product->supplier) {
                    $ids['suppliers'][] = $inventory->product->supplier->id;
                }
            }
        });

        return $ids;
    }

    /**
     * total attribute
     * 
     * @return float|double
     */
    public function getTotalAttribute()
    {
        if (!$this->coupon) {
            return $this->original_total;
        }

        $less = $this->coupon->discount_price;

        if ($this->coupon->discount_type == 'percentage') {
            $less = $this->original_total * ($this->coupon->discount_percentage / 100);
        }

        return $this->original_total - $less;
    }

    /**
     * original_total attribute
     * 
     * @return float|double
     */
    public function getOriginalTotalAttribute()
    {
        $total = 0;

        $this->inventories->map(function ($inventory) use (&$total) {
            $inventory->subtotal = $inventory->price * $inventory->pivot->quantity;

            $total += $inventory->subtotal;

            return $inventory;
        });

        return $total;
    }

    /**
     * is_discounted attribute
     * 
     * @return mixed
     */
    public function getIsDiscountedAttribute()
    {
        return $this->coupon != null;
    }

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
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
