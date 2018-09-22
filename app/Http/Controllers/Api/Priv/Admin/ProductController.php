<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveProduct as SaveModel;
use App\Http\Requests\SaveProductPicture;
use App\Models\Product as Model;

class ProductController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'browse_products'   => ['index', 'all'],
            'create_product'    => ['create'],
            'edit_product'      => ['edit'],
            'delete_product'    => ['delete']
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
            ['id', 'name', 'supplier_id', 'category_id', 'upc']
        );

        $models = Model::search($query);

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
        $model->load('epcs');

        return $model;
    }

    /**
     * Model picture action
     * 
     * @param  Request $request Request object
     * @param  Model $model Model model
     * @return mixed
     */
    public function picture(SaveProductPicture $request, Model $model)
    {
        $file = $request->file('file');

        $model->picture = $file;

        $model->save();

        $this->admin->user()->log('products:set_picture', [
            'name' => $model->name
        ]);

        return $model;
    }

    /**
     * Get all model in JSON
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function all()
    {
        return Model::all();
    }

    /**
     * Create model action
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function create(SaveModel $request)
    {
        $data = $request->only(['supplier_id', 'category_id', 'upc', 'name', 'description', 'epcs']);

        $model = Model::create($data);

        $model->syncEpcs($data['epcs']);

        $this->admin->user()->log('products:create', [
            'name' => $model->name
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
        $data = $request->only(['supplier_id', 'category_id', 'upc', 'name', 'description', 'epcs']);

        $model->fill($data);
        $model->save();

        $model->syncEpcs($data['epcs']);

        $this->admin->user()->log('products:edit', [
            'name' => $model->name
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
        $model->delete();

        $this->admin->user()->log('products:delete', [
            'name' => $model->name
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

        $this->admin->user()->log('products:restore', [
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

        $this->admin->user()->log('products:destroy', [
            'name' => $model->name
        ]);

        return response('', 204);
    }
}
