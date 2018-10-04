<?php

namespace App\Http\Controllers\Api\Kiosk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\TagReceive;
use App\Models\ClearedEpc;
use App\Models\ProductEpc;
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

    /**
     * Get products by EPC
     * @param  Request $request [description]
     * @return array
     */
    public function products(Request $request)
    {
        $epcs = $request->input();
        $products = collect([]);

        ProductEpc::whereIn('code', $epcs)->get()
            ->each(function (ProductEpc $epc) use (&$products) {
                $id = $epc->product->id;
                $quantity = 0;
                $epcs = [];

                if (isset($products[$id])) {
                    $quantity = $products[$id]['quantity'];
                    $epcs = $products[$id]['epcs'];
                }

                $epcs[] = $epc->code;
                
                $products[$id] = [
                    'product_id' => $id,
                    'quantity' => $quantity + 1,
                    'epcs' => $epcs
                ];
            });

        return $products->values();
    }

    /**
     * Check item clearance by EPC
     * 
     * @param  Request $request Request object
     * @return array
     */
    public function clearance(Request $request)
    {
        $epcs = $request->input();

        return ClearedEpc::check($epcs);
    }
}
