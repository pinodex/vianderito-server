<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveManufacturer as SaveModel;
use App\Models\Manufacturer as Model;

class ManufacturerController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'browse_manufacturers'  => ['index', 'all'],
            'create_manufacturer'   => ['create'],
            'edit_manufacturer'     => ['edit'],
            'delete_manufacturer'   => ['delete']
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
            ['id', 'name']
        );

        $models = Model::search($query);

        if ($relations = $request->input('with')) {
            $models->with(explode(',', $relations));
        }

        $models->withCount('products');

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
        $data = $request->only(['name', 'code']);

        $model = Model::create($data);

        $this->admin->user()->log('manufacturers:create', [
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
        $data = $request->only(['name', 'code']);

        $model->fill($data);
        $model->save();

        $this->admin->user()->log('manufacturers:edit', [
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

        $this->admin->user()->log('manufacturers:delete', [
            'name' => $model->name
        ]);

        return response('', 204);
    }
}
