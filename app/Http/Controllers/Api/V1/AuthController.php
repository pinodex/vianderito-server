<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only(['id', 'password']);

        $status = $this->web->attempt($credentials);

        if ($status)
            return $this->web->user();

        abort(401, 'Invalid credentials');
    }
}
