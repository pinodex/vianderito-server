<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveDepartment as SaveModel;
use App\Models\Department as Model;

class DepartmentController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'browse_departments'   => ['index', 'all'],
            'create_department'    => ['create'],
            'edit_department'      => ['edit', 'permissions'],
            'delete_department'    => ['delete']
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

        if ($request->input('trashed') == true) {
            $models->onlyTrashed();
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

        $this->admin->user()->log('departments:create', [
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

        $this->admin->user()->log('departments:edit', [
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

        $this->admin->user()->log('departments:set_permissions', [
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
        if ($model->id == $this->admin->user()->department->id) {
            abort(422, 'You cannot delete your own department');
        }

        $model->delete();

        $this->admin->user()->log('departments:delete', [
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

        $this->admin->user()->log('departments:restore', [
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

        $model->accounts()->withTrashed()->update(['department_id' => null]);
        $model->permissions()->sync([]);

        $model->forceDelete();

        $this->admin->user()->log('departments:destroy', [
            'name' => $model->name
        ]);

        return response('', 204);
    }
}
