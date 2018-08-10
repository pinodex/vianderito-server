<?php

namespace App\Http\Controllers\Api\V1\Verify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Authy\AuthyApi;

class SmsVerificationController extends Controller
{
    protected $user;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('base_auth:api');

        $this->user = $this->api->user();
    }

    /**
     * Start SMS verification process
     * 
     * @param  Request  $request Request object
     * @param  AuthyApi $authy   AuthyApi object
     * @return mixed
     */
    public function start(Request $request, AuthyApi $authy)
    {
        if (!$this->user->phone_number) {
            return abort(403, [
                'message' => 'You have not set a phone number for your account'
            ]);
        }

        $response = $authy->phoneVerificationStart(
            $this->user->phone_number, '63', 'sms');

        return [
            'message' => 'Verification started',
            'response' => $response
        ];
    }

    /**
     * Verify SMS verification code
     * 
     * @param  Request  $request Request object
     * @param  AuthyApi $authy   Authy object
     * @return mixed
     */
    public function verify(Request $request, AuthyApi $authy)
    {
        if (!$this->user->phone_number) {
            return abort(403, [
                'message' => 'You have not set a phone number for your account'
            ]);
        }

        $code = $request->input('code');

        $response = $authy->phoneVerificationCheck(
            $this->user->phone_number, '63', $code);

        if ($response->ok()) {
            $this->user->verify();

            return [
                'message' => 'Phone number has been successfully verified'
            ];
        }

        return response()->json([
            'message' => $response->message()
        ], 400);
    }
}
