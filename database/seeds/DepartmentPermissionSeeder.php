<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Department;

class DepartmentPermissionSeeder extends Seeder
{
    private $permissions = [
        'Sales & Marketing' => [
            'browse_products', 'browse_categories', 'browse_suppliers', 'browse_inventories',
            'browse_coupons', 'create_coupon', 'delete_coupon', 'edit_coupon'
        ],

        'Inventory Manager' => [
            'browse_categories', 'create_category', 'delete_category', 'edit_category',
            'browse_suppliers', 'create_supplier', 'delete_supplier', 'edit_supplier',
            'browse_products', 'create_product', 'delete_product', 'edit_product',
            'browse_inventories', 'create_inventory', 'delete_inventory', 'edit_inventory'
        ],

        'User Support' => [
            'browse_users', 'create_user', 'delete_user', 'edit_user'
        ],

        'Audit' => [
            'browse_products',
            'browse_categories',
            'browse_suppliers',
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
        
        Department::where('name', 'Administrator')
            ->first()
            ->permissions()
            ->sync($all);

        foreach ($this->permissions as $departmentName => $permissions) {
            Department::where('name', $departmentName)
                ->first()
                ->permissions()
                ->sync($permissions);
        }
    }
}
