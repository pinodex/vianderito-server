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
            ['eid', 'product_id']
        );

        $models = Model::search($query);

        $models->has('product')->with('product');

        if ($relations = $request->input('with')) {
            $models->with(explode(',', $relations));
        }

        if ($request->input('trashed') == true) {
            $models->onlyTrashed();
        }

        $result = $models->paginate(20);

        return $result;
    }

    /**
     * Get models by IDs
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function byId(Request $request)
    {
        $id = $request->input('id', []);

        $models = Model::searchByIds($id);

        $models->has('product')->with('product');

        if ($relations = $request->input('with')) {
            $models->with(explode(',', $relations));
        }

        return $models->get();
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
        $model->load('product');
        
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
            'eid', 'product_id', 'stocks', 'critical_stocks',
            'cost', 'price', 'batch_date', 'expiration_date'
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
            'eid', 'product_id', 'stocks', 'critical_stocks',
            'cost', 'price', 'batch_date', 'expiration_date'
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

     /**
     * Restore account action
     * 
     * @param  Request $request Request object
     * @param  string   $id     Model id
     * @return mixed
     */
    public function restore(Request $request, $id)
    {
        $model = Model::withTrashed()->findOrFail($id);

        $model->restore();

        $this->admin->user()->log('inventories:restore', [
            'name' => $model->name
        ]);

        return response('', 204);
    }

    /**
     * Destroy account action
     * 
     * @param  Request $request Request object
     * @param  string   $id     Model id
     * @return mixed
     */
    public function destroy(Request $request, $id)
    {
        $model = Model::withTrashed()->findOrFail($id);

        $model->forceDelete();

        $this->admin->user()->log('inventories:destroy', [
            'name' => $model->name
        ]);

        return response('', 204);
    }
}
