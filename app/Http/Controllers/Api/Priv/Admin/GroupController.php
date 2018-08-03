<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveGroup as SaveModel;
use App\Models\Group as Model;

class GroupController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'browse_groups'   => ['index', 'all'],
            'create_group'    => ['create'],
            'edit_group'      => ['edit', 'permissions'],
            'delete_group'    => ['delete']
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

        $models->withCount('accounts');

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
        $data = $request->only(['name']);

        $model = Model::create($data);

        $this->admin->user()->log('groups:create', [
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
        $data = $request->only(['name']);

        $model->fill($data);
        $model->save();

        $this->admin->user()->log('groups:edit', [
            'name' => $model->name
        ]);

        return $model;
    }

    /**
     * Model permission action
     * 
     * @param  Request $request Request object
     * @param  Model   $model   Model object
     * @return mixed
     */
    public function permissions(Request $request, Model $model)
    {
        $ids = $request->input('ids');

        $model->permissions()->sync($ids);

        $this->admin->user()->log('groups:set_permissions', [
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
        if ($model->id == $this->admin->user()->group->id) {
            abort(422, 'You cannot delete your own group');
        }

        $model->delete();

        $this->admin->user()->log('groups:delete', [
            'name' => $model->name
        ]);

        return response('', 204);
    }
}
