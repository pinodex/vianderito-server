<?php

namespace App\Http\Controllers\Api\V1\Password;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SmsReset;
use App\Models\User;

class SmsResetController extends Controller
{
    /**
     * Start SMS verification procedure
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function start(SmsReset $request)
    {
        $phone = $request->input('phone_number');

        $user = User::findByPhoneNumber($phone);

        if (!$user) {
            return response()->json([
                'message' => 'Cannot find user with phone ' . $phone
            ], 422);
        }

        $user->sendSmsVerification();

        return response(null, 202);
    }

    /**
     * Get password reset token if SMS verification is completed
     * 
     * @param  SmsReset $request Request object
     * @return mixed
     */
    public function token(SmsReset $request)
    {
        $phone = $request->input('phone_number');
        $code = $request->input('code');

        $user = User::findByPhoneNumber($phone);

        if (!$user) {
            return response()->json([
                'message' => 'Cannot find user with phone ' . $phone
            ], 422);
        }

        $checkCode = $user->verifySmsCode($code);

        if (!$checkCode) {
            return response()->json([
                'message' => 'Invalid phone number verification code'
            ], 422);
        }

        $passwordReset = $user->requestPasswordReset();

        return [
            'user_id' => $user->id, 
            'token' => $passwordReset->token,
            'expires_at' => (string) $passwordReset->expires_at
        ];
    }
}
