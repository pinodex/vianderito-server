<?php

namespace App\Events\Kiosk;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Models\ProductEpc;
use App\Models\Product;

class CartUpdate implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($epcs)
    {
        $quantites = collect([]);
        
        $data = $epcs->each(function (ProductEpc $epc) use ($quantites) {
            $currentQuantity = $quantites->get($epc->product->id) ?? 0;

            $quantites->put($epc->product->id, $currentQuantity + 1);
        })
        ->unique(function (ProductEpc $epc) {
            return $epc->product->id;
        })
        ->map(function (ProductEpc $epc) use ($quantites) {
            $quantity = $quantites->get($epc->product->id);
            $price = $epc->product->frontInventory->price;

            return [
                'product_id' => $epc->product_id,
                'quantity' => $quantity,
                'subtotal' => $quantity * $price,
                'product' => $epc->product
            ];
        });

        $this->data = $data->values()->all();
    }

    /**
     * The event's broadcast name.
     *
     * @return string
     */
    public function broadcastAs()
    {
        return 'cart.update';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('kiosk');
    }

    /**
     * Get the data to broadcast.
     *
     * @return array
     */
    public function broadcastWith()
    {
        return $this->data;
    }
}
