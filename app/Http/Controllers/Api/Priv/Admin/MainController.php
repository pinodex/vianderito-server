<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Hash;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\AccountDisabledException;

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
    public function changePassword(Request $request) {
        $data = $request->only(['current_password', 'new_password']);

        $account = $this->admin->user();

        $check = Hash::check($data['current_password'], $account->password);

        if (!$check) {
            return response()->json([
                'message' => 'Current password does not match'
            ], 422);
        }

        if (strlen($data['new_password']) < 8) {
            return response()->json([
                'message' => 'Password must be at least 8 characters'
            ], 422);
        }

        $account->password = $data['new_password'];
        $account->require_password_change = false;
        
        $account->save();

        $this->admin->user()->log('account:change_password');

        return response('', 204);
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
