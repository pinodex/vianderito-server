<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Requests\SaveInventoryLoss;
use App\Http\Controllers\Controller;
use App\Models\InventoryLoss as Model;
use App\Models\Inventory;

class InventoryLossController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'browse_inventories'       => ['index']
        ]);
    }

    /**
     * Get losses for inventory
     * 
     * @param  Request   $request   Request object
     * @param  Inventory $inventory Inventory model
     * @return mixed
     */
    public function index(Request $request, Inventory $inventory)
    {
        return $inventory->losses;
    }

    /**
     * Create loss for inventory
     * 
     * @param  Request   $request   Request object
     * @param  Inventory $inventory Inventory model
     * @return mixed
     */
    public function create(SaveInventoryLoss $request, Inventory $inventory)
    {
        $data = $request->only('units', 'remarks');

        $inventory->losses()->create($data);

        $this->admin->user()->log('inventory_losses:create', [
            'eid' => $inventory->eid,
            'product' => $inventory->product->name,
            'units' => $data['units']
        ]);

        return response(null, 202);
    }

    /**
     * Edit loss for inventory
     * 
     * @param  Request   $request   Request object
     * @param  Inventory $inventory Inventory model
     * @return mixed
     */
    public function edit(SaveInventoryLoss $request, Inventory $inventory, Model $model)
    {
        $data = $request->only('units', 'remarks');

        $model->fill($data);
        $model->save();

        $this->admin->user()->log('inventory_losses:edit', [
            'eid' => $inventory->eid,
            'product' => $inventory->product->name,
            'units' => $data['units']
        ]);

        return response(null, 204);
    }

    /**
     * Delete inventory loss
     * 
     * @param  Request   $request   Request object
     * @param  Inventory $inventory Inventory model
     * @return mixed
     */
    public function delete(Request $request, Inventory $inventory, Model $model)
    {
        $model->delete();

        $this->admin->user()->log('inventory_losses:delete', [
            'eid' => $inventory->eid,
            'product' => $inventory->product->name,
            'units' => $model->units
        ]);

        return response(null, 204);
    }
}
