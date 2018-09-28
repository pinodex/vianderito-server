<?php

namespace App\Http\Controllers\Api\V1\Cart;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\Kiosk\NewTransaction;
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
        if ($model->status != 'pending') {
            abort(401);
        }

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
        if ($model->status != 'pending') {
            return response()->json([
                'message' => 'Transaction is not pending'
            ], 401);
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
    public function delete(Request $request, $id)
    {
        $transaction = Transaction::find($id);

        if ($transaction && $transaction->status == 'pending') {
            $transaction->delete();
        }

        event(new NewTransaction);

        return response(null, 202);
    }
}
