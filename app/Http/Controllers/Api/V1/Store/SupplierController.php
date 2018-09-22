<?php

namespace App\Http\Controllers\Api\V1\Store;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Supplier;

class SupplierController extends Controller
{
    /**
     * Supplier list
     * 
     * @return mixed
     */
    public function index()
    {
        $suppliers = Supplier::query()->withCount('products');

        return $suppliers->get();
    }

    /**
     * View specific supplier
     * 
     * @param  Product $model Product model
     * @return Product
     */
    public function view(Request $request, Supplier $model)
    {
        return $model;
    }

    /**
     * View specific supplier by code
     * 
     * @param  Product $model Product model
     * @return Product
     */
    public function viewCode(Request $request, $code)
    {
        $supplier = Supplier::where('code', $code)->firstOrFail();
        
        return $this->view($request, $supplier);
    }
}
