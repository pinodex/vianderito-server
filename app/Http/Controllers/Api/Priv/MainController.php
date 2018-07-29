<?php

namespace App\Http\Controllers\Api\Priv;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function session()
    {
        $auth = [
            'web' => null,
            'admin' => null
        ];

        if ($this->web->check()) {
            $auth['web'] = $this->web->user();
        }

        if ($this->admin->check()) {
            $auth['admin'] = $this->admin->user();
        }

        return $auth;
    }
}
