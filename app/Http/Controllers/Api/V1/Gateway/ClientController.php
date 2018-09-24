<?php

namespace App\Http\Controllers\Api\V1\Gateway;

use Braintree\Gateway;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ClientController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('jwt.auth');
    }

    /**
     * Generate client token
     * 
     * @return array
     */
    public function token(Request $request, Gateway $gateway)
    {
        return [
            'token' => $gateway->clientToken()->generate()
        ];
    }
}
