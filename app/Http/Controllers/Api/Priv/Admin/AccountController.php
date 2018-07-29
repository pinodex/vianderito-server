<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveAccount as SaveModel;
use App\Models\Account as Model;

class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'browse_accounts'   => ['index', 'view'],
            'create_account'    => ['create'],
            'edit_account'      => ['edit', 'avatar'],
            'delete_account'    => ['delete']
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
            ['first_name', 'middle_name', 'last_name', 'username', 'group_id']
        );

        $models = Model::search($query);

        if ($relations = $request->input('with')) {
            $models->with(explode(',', $relations));
        }

        $result = $models->paginate(20);

        return $result;
    }

    /**
     * Create model action
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function create(SaveModel $request)
    {
        $data = $request->only(
            ['first_name', 'middle_name', 'last_name', 'username', 'group_id']
        );

        $model = new Model($data);
        $password = $model->generatePassword();

        $model->require_password_change = true;

        $model->save();

        $model->generated_password = $password;

        $this->admin->user()->log('accounts:create', [
            'name' => $model->name
        ]);

        return $model;
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
     * Logs account action
     * 
     * @param  Request $request Request object
     * @param  Model $model Model Model object
     * @return mixed
     */
    public function logs(Request $request, Model $model)
    {
        return $model->logs()->paginate(20);
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
            ['first_name', 'middle_name', 'last_name', 'username', 'group_id']
        );

        $model->fill($data);
        $model->save();

        $this->admin->user()->log('accounts:edit', [
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
    public function avatar(Request $request, Model $model)
    {
        $file = $request->file('file');

        $model->picture = $file;

        $model->save();

        $this->admin->user()->log('accounts:set_avatar', [
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
        if ($model->id == $this->admin->user()->id) {
            abort(422, 'You cannot delete your own account');
        }

        $model->delete();
        $model->deletePicture();

        $this->admin->user()->log('accounts:delete', [
            'name' => $model->name
        ]);

        return response('', 204);
    }
}
