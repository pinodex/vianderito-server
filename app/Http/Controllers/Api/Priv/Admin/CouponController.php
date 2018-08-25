<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\SaveCoupon as SaveModel;
use App\Models\Coupon as Model;

class CouponController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'browse_coupons'   => ['index', 'all'],
            'create_coupon'    => ['create'],
            'edit_coupon'      => ['edit'],
            'delete_coupon'    => ['delete']
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
            [] // Todo
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
        // Trick to eager load selections custom attribute
        $model->selections = $model->selections;

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
            'code', 'description', 'validity_start', 'validity_end', 'discount_type',
            'discount_price', 'discount_percentage', 'discount_floor_price',
            'discount_ceiling_price', 'selections'
        ]);

        $model = Model::create($data);

        $model->syncSelections($data['selections']);

        $this->admin->user()->log('coupons:create', [
            'code' => $model->code
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
            'code', 'description', 'validity_start', 'validity_end', 'discount_type',
            'discount_price', 'discount_percentage', 'discount_floor_price',
            'discount_ceiling_price', 'selections'
        ]);

        $model->fill($data);
        
        $model->syncSelections($data['selections']);
        
        $model->save();

        $this->admin->user()->log('coupons:edit', [
            'code' => $model->code
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

        $this->admin->user()->log('coupons:delete', [
            'code' => $model->code
        ]);

        return response('', 204);
    }
}
