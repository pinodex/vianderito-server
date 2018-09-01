<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;

class MainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin')->except(['login', 'passwordReset']);
        $this->middleware('guest:admin')->only('login');

        parent::__construct();
    }

     /**
     * Index page
     * 
     * @return mixed
     */
    public function index(Request $request)
    {
        return view('admin.index');
    }

    /**
     * Login page
     * 
     * @return mixed
     */
    public function login(Request $request)
    {
        return view('admin.login');
    }

    /**
     * Password reset page
     *
     * @param Request $request Request object
     * @param Account $account Account model
     * 
     * @return mixed
     */
    public function passwordReset(Request $request, Account $account)
    {
        return view('admin.password_reset', [
            'account' => $account
        ]);
    }
}
