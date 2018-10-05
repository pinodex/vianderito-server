<?php

namespace App\Http\Controllers\Api\Kiosk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\Kiosk\CartUpdate;
use App\Models\Transaction;

class TransactionController extends Controller
{
    /**
     * Create new transaction
     * 
     * @return Transaction
     */
    public function create()
    {
        return Transaction::create([
            'status' => Transaction::STATUS_PENDING
        ]);
    }

    /**
     * Set transaction selected products
     * 
     * @param Request $request Request object
     * @param Transaction $model Transaction instance
     */
    public function setProducts(Request $request, Transaction $model)
    {
        if ($model->status != Transaction::STATUS_PENDING) {
            return response(null, 204);
        }

        $ids = $request->input();

        $model->addInventoriesFromProductIds($ids);

        $model->load('inventories.product');

        $data = $model->inventories->map(function ($entry) {
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
        });

        event(new CartUpdate($data));

        return response(null, 202);
    }
}
