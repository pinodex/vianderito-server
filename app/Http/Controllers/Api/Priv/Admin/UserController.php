<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUser as SaveModel;
use App\Http\Requests\SaveAvatar;
use App\Models\User as Model;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'browse_users'   => ['index', 'view'],
            'edit_user'      => ['edit'],
            'delete_user'    => ['delete']
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
            ['name', 'username', 'email_address', 'password']
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
     * Get user payment methods
     * 
     * @param  Request $request Request object
     * @param  Model   $model   Model model
     * @return mixed
     */
    public function paymentMethods(Request $request, Model $model)
    {
        return $model->gatewayCustomers()->paginate(20);
    }

    /**
     * Get user payments
     * 
     * @param  Request $request Request object
     * @param  Model   $model   Model model
     * @return mixed
     */
    public function payments(Request $request, Model $model)
    {
        return $model->payments()->paginate(20);
    }

    /**
     * Get user purchase
     * 
     * @param  Request $request Request object
     * @param  Model   $model   Model model
     * @return mixed
     */
    public function purchases(Request $request, Model $model)
    {
        return $model->purchases()->with('products')->paginate(20);
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
        $data = $request->only(
            ['name', 'username', 'email_address', 'phone_number', 'is_verified']
        );

        $model->fill($data);
        $model->save();

        $this->admin->user()->log('users:edit', [
            'name' => $model->name
        ]);

        return $model;
    }

    /**
     * Avatar model action
     * 
     * @param  Request $request Request object
     * @param  Model $model Model model
     * @return mixed
     */
    public function avatar(SaveAvatar $request, Model $model)
    {
        $file = $request->file('file');

        $model->picture = $file;

        $model->save();

        $this->admin->user()->log('users:set_avatar', [
            'name' => $model->name
        ]);

        return response('', 204);
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

        $this->admin->user()->log('users:delete', [
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

        $this->admin->user()->log('users:restore', [
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

        $this->admin->user()->log('users:destroy', [
            'name' => $model->name
        ]);

        return response('', 204);
    }
}
