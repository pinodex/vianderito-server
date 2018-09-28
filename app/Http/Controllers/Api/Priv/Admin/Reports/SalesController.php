<?php

namespace App\Http\Controllers\Api\Priv\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Purchase;

class SalesController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'create_reports'  => ['index', 'graph', 'graphOptions'],
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
            ->each->append([
                'total_loss', 'total_loss_cost', 'total_loss_price',
                'total_sale', 'total_sale_cost', 'total_sale_price',
                'stocks'
            ]);

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
        $view = $request->input('view') ?? 'monthly';
        $date = $request->input('date') ?? now();

        return Purchase::getSalesGraph($view, $date);
    }

    /**
     * Graph options
     * 
     * @return array
     */
    public function graphOptions()
    {
        $years = Purchase::all()->unique(function ($item) {
            return $item['created_at']->year;
        })->map(function ($item) {
            return (string)$item['created_at']->year;
        })->sort()->toArray();

        $months = [
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        ];

        return [
            'years' => $years,
            'months' => $months,

            'now' => [
                'year' => date('Y'),
                'month' => date('n')
            ]
        ];
    }
}
