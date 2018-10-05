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
    public function __construct($inventories)
    {
        $this->data = $inventories->map(function ($entry) {
            return [
                'product' => [
                    'name' => $entry->product->name,
                    'picture' => $entry->product->picture
                ],

                'price' => $entry->price,

                'pivot' => [
                    'quantity' => $entry->pivot->quantity
                ]
            ];
        })->toArray();
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
