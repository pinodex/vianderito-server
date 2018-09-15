<?php

namespace App\Http\Controllers\Api\V1\Password;

use Mail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EmailReset;
use App\Mail\UserPasswordReset;
use App\Models\User;

class EmailResetController extends Controller
{
    public function start(EmailReset $request)
    {
        $email = $request->input('email_address');

        $user = User::where('email_address', $email)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Cannot find user with email ' . $email
            ], 422);
        }

        $passwordReset = $user->requestPasswordReset();

        Mail::to($user)->send(new UserPasswordReset($passwordReset));

        return [
            'user_id' => $user->id
        ];
    }
}
