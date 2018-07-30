<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveCategory as SaveModel;
use App\Models\Category as Model;

class CategoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'browse_categories'   => ['index', 'all'],
            'create_category'     => ['create'],
            'edit_category'       => ['edit'],
            'delete_category'     => ['delete']
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
            ['name']
        );

        $models = Model::search($query);

        if ($relations = $request->input('with')) {
            $models->with(explode(',', $relations));
        }

        // $models->where('parent_id', null)->with('categories');

        $result = $models->paginate(20);

        return $result;
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
        $data = $request->only(['name', 'description']);

        $model = Model::create($data);

        $this->admin->user()->log('categories:create', [
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
        $data = $request->only(['name']);

        $model->fill($data);
        $model->save();

        $this->admin->user()->log('categories:edit', [
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

        $this->admin->user()->log('categories:delete', [
            'name' => $model->name
        ]);

        return response('', 204);
    }
}
