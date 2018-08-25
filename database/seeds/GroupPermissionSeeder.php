<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Group;

class GroupPermissionSeeder extends Seeder
{
    private $permissions = [
        'Sales & Marketing' => [
            'browse_products', 'browse_categories', 'browse_manufacturers', 'browse_inventories',
            'browse_coupons', 'create_coupon', 'delete_coupon', 'edit_coupon'
        ],

        'Inventory Manager' => [
            'browse_categories', 'create_category', 'delete_category', 'edit_category',
            'browse_manufacturers', 'create_manufacturer', 'delete_manufacturer', 'edit_manufacturer',
            'browse_products', 'create_product', 'delete_product', 'edit_product',
            'browse_inventories', 'create_inventory', 'delete_inventory', 'edit_inventory'
        ],

        'User Support' => [
            'browse_users', 'create_user', 'delete_user', 'edit_user'
        ],

        'Audit' => [
            'browse_products',
            'browse_categories',
            'browse_manufacturers',
            'browse_inventories',
            'browse_coupons'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $all = Permission::all()->pluck('id');
        
        Group::where('name', 'Administrator')
            ->first()
            ->permissions()
            ->sync($all);

        foreach ($this->permissions as $groupName => $permissions) {
            Group::where('name', $groupName)
                ->first()
                ->permissions()
                ->sync($permissions);
        }
    }
}
