<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Group as Model;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->only(
            ['name']
        );

        $models = Model::search($query);

        if ($relations = $request->input('with')) {
            $models->with(explode(',', $relations));
        }

        $result = $models->paginate(20);

        return $result;
    }

    public function all()
    {
        return Model::all();
    }
}
