<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveProduct as SaveModel;
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
            ['name', 'manufacturer_id', 'category_id', 'upc']
        );

        $models = Model::search($query);

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
        $data = $request->only(['manufacturer_id', 'category_id', 'upc', 'name', 'description']);

        $model = Model::create($data);

        $this->admin->user()->log('products:create', [
            'name' => $model->name
        ]);

        return response('', 204);
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
        $data = $request->only(['manufacturer_id', 'category_id', 'upc', 'name', 'description']);

        $model->fill($data);
        $model->save();

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
}
