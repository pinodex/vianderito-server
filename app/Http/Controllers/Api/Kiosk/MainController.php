<?php

namespace App\Http\Controllers\Api\Kiosk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\Kiosk\CartUpdate;
use App\Models\Product;

class MainController extends Controller
{
    /**
     * Kiosk index page
     * 
     * @return array
     */
    public function index()
    {
        return [
            'id' => 'com.raphaelmarco.vianderito.kiosk',
            'application' => 'Vianderito',
            'version' => 1
        ];
    }

    /**
     * Broadcast cart changes with product EPC
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function cart(Request $request)
    {
        $epcs = $request->input('epcs');
        $models = Product::getProductsByEpcs($epcs);

        event(new CartUpdate($models));

        return response(null, 202);
    }
}