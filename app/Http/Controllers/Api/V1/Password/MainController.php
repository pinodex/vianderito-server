<?php

namespace App\Http\Controllers\Api\V1\Password;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\NewPassword;
use App\Models\User;

class MainController extends Controller
{
    /**
     * Perform password reset for user
     * 
     * @param  NewPassword $request Request object
     * @param  User        $user    User object
     * @return mixed
     */
    public function reset(NewPassword $request, User $user)
    {
        $data = $request->only(['token', 'new_password']);

        $resetRequest = $user->findPasswordResetRequest($data['token']);

        if (!$resetRequest) {
            return response()->json([
                'message' => 'Password reset cannot completed'
            ], 403);
        }

        $user->password = $data['new_password'];
        $user->save();

        $resetRequest->delete();

        return response('', 202);
    }
}
