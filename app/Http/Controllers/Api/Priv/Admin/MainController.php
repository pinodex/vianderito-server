<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\AccountDisabledException;
use App\Http\Requests\NewPassword;
use App\Models\Account;

class MainController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth:admin')->only('changePassword');
    }

    /**
     * Login action
     *
     * @param Request $request Request object
     * @return mixed
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['id', 'password']);

        try {
            $login = $this->admin->attempt($credentials);
        } catch (AccountDisabledException $e) {
            return response([
                'error' => 'Account has been disabled'
            ], 403);
        }

        if ($login) {
            $this->admin->user()->log('account:login');

            return response('', 204);
        }

        return response([
            'error' => 'Invalid username and/or password'
        ], 403);
    }

    /**
     * Logout action
     *
     * @param Request $request Request object
     * @return mixed
     */
    public function logout(Request $request)
    {
        $this->admin->logout();

        return response('', 204);
    }

    /**
     * Change password action
     * 
     * @param  Request $request Request object
     * 
     * @return mixed
     */
    public function changePassword(NewPassword $request) {
        $data = $request->only(['current_password', 'new_password']);

        $account = $this->admin->user();

        $check = Hash::check($data['current_password'], $account->password);

        if (!$check) {
            return response()->json([
                'message' => 'Current password does not match'
            ], 403);
        }

        $account->password = $data['new_password'];
        $account->require_password_change = false;
        
        $account->save();

        $this->admin->user()->log('account:change_password');

        return response('', 204);
    }

    /**
     * Request password change action
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function requestPasswordReset(Request $request)
    {
        $data = $request->only(['email']);

        $account = Account::where('email', $data['email'])->first();

        if ($account) {
            $account->requestPasswordReset();
        }

        return response('', 202);
    }

    /**
     * Reset password action
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function resetPassword(NewPassword $request, Account $account)
    {
        $data = $request->only(['token', 'new_password']);

        $resetRequest = $account->findPasswordResetRequest($data['token']);

        if (!$resetRequest) {
            return response()->json([
                'message' => 'Password reset cannot completed'
            ], 403);
        }

        if ($resetRequest->hasExpired()) {
            return response()->json([
                'message' => 'The password reset link has expired'
            ], 403);
        }

        $account->password = $data['new_password'];
        $account->save();

        $resetRequest->delete();

        return response('', 202);
    }

    /**
     * Get permissions allowed in a auth session
     * 
     * @return array
     */
    public function permissions()
    {
        return $this->admin->user()->group->permissions;
    }
}
