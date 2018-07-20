<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except('login');
        $this->middleware('guest:admin')->only('login');

        parent::__construct();
    }

    public function index(Request $request)
    {
        return view('admin.index');
    }

    /**
     * Login action
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function login(Request $request)
    {
        if ($request->method() == 'POST') {
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

        return view('admin.login');
    }
}
