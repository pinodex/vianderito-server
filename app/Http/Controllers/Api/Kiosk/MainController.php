<?php

namespace App\Http\Controllers\Api\Kiosk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\Kiosk\CartUpdate;
use App\Events\TagReceive;
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
        event(new TagReceive($epcs));

        return response(null, 202);
    }

    /**
     * Broadcast EPC
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function epcs(Request $request)
    {
        $ids = $request->input();

        if (count($ids) > 0) {
            event(new TagReceive($ids));
        }

        return response(null, 202);
    }
}
