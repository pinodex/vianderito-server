<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Purchase;

class OrderController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->middleware('jwt.auth');
    }

    /**
     * User order history
     * 
     * @return Collection|array
     */
    public function index()
    {
        return $this->api->user()->purchases()->get()
            ->each->append('products_count');
    }

    /**
     * User view order
     * 
     * @return Collection|array
     */
    public function view(Request $request, Purchase $model)
    {
        $model->load('products.product');
        
        $model->append('products_count');

        return $model;
    }
}
