<?php

namespace App\Http\Controllers\Api\V1\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Payment;

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
        if ($model->status != 'pending') {
            abort(401);
        }

        $model->load('inventories', 'inventories.product');

        $model->total = $model->getTotal();

        return $model;
    }

    /**
     * Purchase transaction
     * 
     * @param  Request     $request     Request object
     * @param  Transaction $transaction Transaction model
     * @return mixed
     */
    public function purchase(Request $request, Transaction $model) {
        if ($model->status != 'pending') {
            abort(401);
        }

        $payment = Payment::findOrFail($request->input('payment_id'));

        if ($payment->transaction != null) {
            return response()->json([
                'message' => 'Cannot authorize payment'
            ], 401);
        }

        if ($payment->amount < $model->getTotal()) {
            return response()->json([
                'message' => 'Insufficient amount'
            ], 401);
        }

        $payment->transaction()->associate($model);
        $payment->save();

        $purchase = $model->moveToPurchases($this->api->user());
        
        $purchase->load('products');

        return $payment;
    }

    /**
     * Delete transaction
     * 
     * @param  Request     $request     Request object
     * @param  Transaction $transaction Transaction model
     * @return mixed
     */
    public function delete(Request $request, Transaction $model) {
        if ($model->status != 'pending') {
            abort(401);
        }

        $model->delete();

        return response(null, 202);
    }
}
