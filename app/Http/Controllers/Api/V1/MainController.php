<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MainController extends Controller
{
    public function index()
    {
        return [
            'id' => 'com.raphaelmarco.vianderito',
            'application' => 'Vianderito',
            'version' => 1
        ];
    }
}
