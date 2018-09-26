<?php

namespace App\Http\Controllers\Api\Priv\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory;

class InventoryController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'create_reports'  => ['index', 'graph'],
        ]);
    }

    /**
     * Inventory report table
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function index(Request $request)
    {
        $dateField = $request->input('date_field') ?? 'batch_date';
        $fromDate = $request->input('from_date') ?? now()->subDays(60);
        $toDate = $request->input('to_date') ?? now();

        $inventories = Inventory::whereBetween($dateField, [$fromDate, $toDate])
            ->orderBy($dateField, 'ASC')
            ->with('product', 'losses')
            ->get()
            ->each->append('total_loss', 'total_sale', 'stocks');

        return $inventories;
    }

    /**
     * Graph data
     * 
     * @param  Request $request Request object
     * @return mixed
     */
    public function graph(Request $request)
    {
        $field = $request->input('field') ?? 'batch_date';

        return Inventory::getGraphByField($field);
    }
}
