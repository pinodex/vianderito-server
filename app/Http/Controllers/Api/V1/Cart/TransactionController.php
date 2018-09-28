<?php

namespace App\Http\Controllers\Api\V1\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\Kiosk\NewTransaction;
use App\Events\PurchaseComplete;
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

        $total = 0;

        $model->load('inventories', 'inventories.product');

        $model->inventories->map(function ($inventory) use (&$total) {
            $inventory->subtotal = $inventory->price * $inventory->pivot->quantity;

            $total += $inventory->subtotal;

            return $inventory;
        });

        $model->total = $total;

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
            return response()->json([
                'message' => 'Transaction is not pending'
            ], 401);
        }

        $payment = Payment::findOrFail($request->input('payment_id'));

        $total = 0;

        $model->inventories->map(function ($inventory) use (&$total) {
            $total += $inventory->price * $inventory->pivot->quantity;
        });

        if ($payment->transaction != null || $model->payment != null) {
            return response()->json([
                'message' => 'Cannot redeclare payment'
            ], 401);
        }

        if ($payment->amount < $total) {
            return response()->json([
                'message' => 'Insufficient amount'
            ], 401);
        }

        $payment->transaction()->associate($model);
        $payment->save();

        $purchase = $model->moveToPurchases($this->api->user());
        
        $purchase->load('products');

        event(new PurchaseComplete($purchase));

        return $payment;
    }

    /**
     * Delete transaction
     * 
     * @param  Request     $request     Request object
     * @param  string      $id Transaction id
     * @return mixed
     */
    public function delete(Request $request, $id) {
        $transaction = Transaction::find($id);

        if ($transaction && $transaction->status == 'pending') {
            $transaction->delete();
        }

        event(new NewTransaction);

        return response(null, 202);
    }
}
