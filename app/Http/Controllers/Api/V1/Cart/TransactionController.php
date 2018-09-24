<?php

namespace App\Http\Controllers\Api\V1\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;

class TransactionController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('jwt.auth');
    }

    /**
     * Get transaction
     * 
     * @param  Request     $request     Request object
     * @param  Transaction $transaction Transaction model
     * @return mixed
     */
    public function get(Request $request, Transaction $model) {
        $model->load('inventories', 'inventories.product');

        $total = 0;

        $model->inventories->each(function ($inventory) use (&$total) {
            $total += $inventory->subtotal;
        });

        $model->total = $total;

        return $model;
    }
}
