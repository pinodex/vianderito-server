<?php

namespace App\Http\Controllers\Api\V1\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Manufacturer;

class ManufacturerController extends Controller
{
    /**
     * Manufacturer list
     * 
     * @return mixed
     */
    public function index()
    {
        $manufacturers = Manufacturer::query()->withCount('products');

        return $manufacturers->get();
    }

    /**
     * View specific manufacturer
     * 
     * @param  Product $model Product model
     * @return Product
     */
    public function view(Request $request, Manufacturer $model)
    {
        return $model;
    }

    /**
     * View specific manufacturer by code
     * 
     * @param  Product $model Product model
     * @return Product
     */
    public function viewCode(Request $request, $code)
    {
        $manufacturer = Manufacturer::where('code', $code)->firstOrFail();
        
        return $this->view($request, $manufacturer);
    }
}
