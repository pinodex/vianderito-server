<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    /**
     * Login action
     *
     * @param Request $request Request object
     * @return mixed
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['id', 'password']);

        $login = $this->admin->attempt($credentials);

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
}
