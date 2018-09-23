<?php

namespace App\Http\Controllers\Api\Kiosk;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction as Model;

class TransactionController extends Controller
{
    /**
     * Create new transaction
     * 
     * @return Transaction
     */
    public function create()
    {
        return Model::create([
            'status' => 'pending'
        ]);
    }

    /**
     * Set transaction selected products
     * 
     * @param Request $request Request object
     * @param Model $model Model instance
     */
    public function setProducts(Request $request, Model $model)
    {
        $ids = $request->input();

        $model->addInventoriesFromProductIds($ids);

        return response(null, 202);
    }
}
