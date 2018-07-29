<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function all()
    {
        $acl = resolve('Acl');

        return $acl->getPermissions();
    }
}
