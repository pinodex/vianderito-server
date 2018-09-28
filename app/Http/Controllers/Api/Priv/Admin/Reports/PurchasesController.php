<?php

namespace App\Http\Controllers\Api\Priv\Admin\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Purchase;

class PurchasesController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->acl([
            'create_reports'  => ['index'],
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
        $fromDate = $request->input('from_date') ?? now()->subDays(60);
        $toDate = $request->input('to_date') ?? now();

        $purchases = Purchase::whereBetween('created_at', [$fromDate, $toDate])
            ->orderBy('created_at', 'ASC')
            ->with('transaction', 'coupon', 'products', 'user')
            ->get()->each->append('discount_rate');

        return $purchases;
    }
}
