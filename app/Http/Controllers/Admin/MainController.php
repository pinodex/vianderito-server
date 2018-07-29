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
}
