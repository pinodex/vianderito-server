<?php

namespace App\Http\Controllers\Api\V1\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\Kiosk\NewTransaction;
use App\Events\Kiosk\CartUpdate;
use App\Events\PurchaseComplete;
use App\Models\Transaction;
use App\Models\Payment;
use App\Models\Coupon;

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
    public function get(Request $request, Transaction $model)
    {
        if ($model->status == Transaction::STATUS_COMPLETE) {
            return response()->json([
                'message' => 'Transaction is already complete'
            ], 422);
        }

        $model->load('inventories', 'inventories.product');

        return $model;
    }

    /**
     * Take transaction
     * 
     * @param  Request     $request     Request object
     * @param  Transaction $transaction Transaction model
     * @return mixed
     */
    public function take(Request $request, Transaction $model)
    {
        $user = $this->api->user();

        if ($model->status == Transaction::STATUS_LOCKED && $model->user_id != $user->id) {
            return response()->json([
                'message' => 'Transaction is in use by another user'
            ], 422);
        }

        if ($model->status == Transaction::STATUS_COMPLETE) {
            return response()->json([
                'message' => 'Transaction is already complete'
            ], 422);
        }

        $model->lockTransaction($user);

        $model->load('inventories', 'inventories.product');

        return $model;
    }

    /**
     * Check coupon eligibility to transaction
     * 
     * @param  Request     $request Request object
     * @param  Transaction $model   Transaction model
     * @return mixed
     */
    public function coupon(Request $request, Transaction $model)
    {
        $code = $request->input('code');

        $coupon = Coupon::findByCode($code);

        if (!$coupon || !$coupon->is_valid) {
            return response()->json(['message' => 'Invalid or expired coupon code'], 422);
        }

        $eligible = $coupon->isTransactionEligible($model);
        
        if (!$eligible) {
            return response()->json(['message' => 'This coupon cannot be used in this transaction'], 422);
        }

        $model->coupon()->associate($coupon);
        $model->save();
        
        return response()->json(['message' => 'Coupon applied']);
    }

    /**
     * Remove applied coupon
     * 
     * @param  Request     $request Request object
     * @param  Transaction $model   Transaction model
     * @return mixed
     */
    public function removeCoupon(Request $request, Transaction $model)
    {
        $model->coupon()->dissociate();
        $model->save();
        
        return response()->json(['message' => 'Coupon removed']);
    }

    /**
     * Purchase transaction
     * 
     * @param  Request     $request     Request object
     * @param  Transaction $transaction Transaction model
     * @return mixed
     */
    public function purchase(Request $request, Transaction $model)
    {
        if ($model->status == Transaction::STATUS_PENDING) {
            return response()->json([
                'message' => 'Transaction is still pending'
            ], 422);
        }

        if ($model->status == Transaction::STATUS_COMPLETE) {
            return response()->json([
                'message' => 'Transaction is already complete'
            ], 422);
        }

        $payment = Payment::findOrFail($request->input('payment_id'));
        
        if ($payment->transaction != null || $model->payment != null) {
            return response()->json([
                'message' => 'Cannot redeclare payment'
            ], 401);
        }

        if ($payment->amount < $model->total) {
            return response()->json([
                'message' => 'Insufficient amount'
            ], 401);
        }

        $payment->transaction()->associate($model);
        $payment->save();

        $purchase = $model->moveToPurchases($this->api->user());

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
    public function delete(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if ($transaction && $transaction->status == Transaction::STATUS_PENDING) {
            $transaction->delete();
        }

        event(new NewTransaction);

        return response(null, 202);
    }

    /**
     * Delete transaction item
     * 
     * @param  Request     $request     Request object
     * @param  string      $id Transaction id
     * @return mixed
     */
    public function deleteItem(Request $request, $id)
    {
        $transaction = Transaction::find($id);
        $inventoryId = $request->input('inventory_id');

        if (!$transaction) {
            return response()->json([
                'message' => 'Cannot find inventory'
            ], 422);
        }

        $transaction->inventories()->detach($inventoryId);
        
        $transaction->load('inventories', 'inventories.product');

        event(new CartUpdate($transaction->inventories));

        if ($transaction->inventories->count() == 0) {
            event(new NewTransaction);
        }

        return response()->json([
            'message' => 'Item removed'
        ], 202);
    }
}
