<?php

namespace App\Http\Controllers\Api\Priv\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Product;
use App\Models\Inventory;
use App\Models\Coupon;

class StatsController extends Controller
{
    /**
     * Stats counts action
     * 
     * @return mixed
     */
    public function counts()
    {
        return [
            'users' => User::count(),
            'products' => Product::count(),
            'inventories' => Inventory::whereNotExpired()->count(),
            'active_coupons' => Coupon::whereCouponDateValid()->count()
        ];
    }
}
