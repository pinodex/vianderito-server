<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\GatewayCustomer;
use App\Models\Payment;
use Braintree\Gateway;

class PaymentController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('jwt.auth');
    }

    /**
     * Create payment
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function create(Request $request, Gateway $gateway)
    {
        $data = $request->only(['customer_id', 'amount']);
        $user = $this->api->user();

        $customer = GatewayCustomer::where('id', $data['customer_id'])
            ->where('user_id', $user->id)
            ->first();

        if (!$customer) {
            abort(401);
        }

        $result = $gateway->transaction()->sale([
            'amount' => $data['amount'],
            'paymentMethodToken' => $customer->token
        ]);

        if (!$result->success) {
            $verification = $result->creditCardVerification;

            if ($verification) {
                return response()->json([
                    'status' => $verification->status,
                    'response_code' => $verification->processorResponseCode,
                    'response_text' => $verification->processorResponseText,
                    'message' => $verification->processorResponseText
                ], 422);
            }

            return response()->json([
                'message' => 'Unable to complete payment. Please try again with a different payment method'
            ], 422);
        }

        return Payment::create([
            'user_id' => $user->id,
            'gateway_id' => $result->transaction->id,
            'amount' => $result->transaction->amount,
            'status' => $result->transaction->status
        ]);
    }
}
