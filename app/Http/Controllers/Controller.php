<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Web session guard
     * 
     * @var \Illuminate\Auth\SessionGuard
     */
    protected $web;

    /**
     * Api session guard
     * 
     * @var \Illuminate\Auth\SessionGuard
     */
    protected $api;

    /**
     * Admin session guard
     * 
     * @var \Illuminate\Auth\SessionGuard
     */
    protected $admin;

    public function __construct()
    {
        $this->web = Auth::guard('web');

        $this->api = Auth::guard('api');

        $this->admin = Auth::guard('admin');
    }

    /**
     * Set ACLs for controller actions
     * 
     * @param  array $acls ACL list
     */
    public function acl($acls)
    {
        foreach ($acls as $key => $value) {
            $this->middleware('acl:' . $key)->only($value);
        }
    }
}
