<?php

namespace App\Http\Controllers\Api\V1\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Product list
     * 
     * @return mixed
     */
    public function index(Request $request)
    {
        $query = $request->only(
            ['id', 'name', 'manufacturer_id', 'category_id', 'upc']
        );

        $products = Product::search($query);

        $products->with('frontInventory');

        return $products->paginate(10);
    }

    /**
     * View specific product
     * 
     * @param  Product $model Product model
     * @return Product
     */
    public function view(Request $request, Product $model)
    {
        $model->load('frontInventory');

        return $model;
    }

    /**
     * View specific product by UPC
     * 
     * @param  Product $model Product model
     * @return Product
     */
    public function viewUpc(Request $request, $upc)
    {
        $product = Product::where('upc', $upc)->firstOrFail();
        
        return $this->view($request, $product);
    }
}