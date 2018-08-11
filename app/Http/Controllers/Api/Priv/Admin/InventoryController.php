<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveInventory as SaveModel;
use App\Models\Inventory as Model;

class InventoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'browse_inventories'   => ['index', 'all'],
            'create_inventory'     => ['create'],
            'edit_inventory'       => ['edit'],
            'delete_inventory'     => ['delete']
        ]);
    }

    /**
     * Index json page
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function index(Request $request)
    {
        $query = $request->only(
            ['product_id']
        );

        $models = Model::search($query);

        $models->has('product')->with('product');

        if ($relations = $request->input('with')) {
            $models->with(explode(',', $relations));
        }

        $result = $models->paginate(20);

        return $result;
    }

    /**
     * Model view action
     * 
     * @param  Request $request Request object
     * @param  Model   $model   Model model
     * @return mixed
     */
    public function view(Request $request, Model $model)
    {
        return $model;
    }

    /**
     * Create model action
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function create(SaveModel $request)
    {
        $data = $request->only([
            'eid', 'product_id', 'stocks', 'price', 'batch_date', 'expiration_date'
        ]);

        $model = Model::create($data);

        $product = $model->product;

        $this->admin->user()->log('inventories:create', [
            'product' => $product->name
        ]);

        return $model;
    }

    /**
     * Model edit action
     * 
     * @param  Request $request Request object
     * @param  Model $model   Model model
     * @return mixed
     */
    public function edit(SaveModel $request, Model $model)
    {
        $data = $request->only([
            'stocks', 'price', 'batch_date', 'expiration_date'
        ]);

        $model->fill($data);
        $model->save();

        $product = $model->product;

        $this->admin->user()->log('inventories:edit', [
            'product' => $product->name
        ]);

        return $model;
    }

    /**
     * Delete model action
     * 
     * @param  Request $request Request object
     * @param  Model $model Model model
     * @return mixed
     */
    public function delete(Request $request, Model $model)
    {
        $product = $model->product;

        $model->delete();

        $this->admin->user()->log('inventories:delete', [
            'id' => $model->eid,
            'product' => $product->name
        ]);

        return response('', 204);
    }
}
