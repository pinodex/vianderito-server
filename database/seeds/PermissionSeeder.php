<?php

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    protected $data = [
        [
            'name' => 'account',
            'items' => [
                'browse_accounts' => ['Browse Accounts', 'Allow browsing of accounts'],
                'create_account' => ['Create Account', 'Allow creation of account'],
                'delete_account' => ['Delete Account', 'Allow deletion account'],
                'edit_account' => ['Edit Account', 'Allow editing of account'],
                'status_account' => ['Account Status', 'Modify status of account']
            ]
        ],

        [
            'name' => 'group',
            'items' => [
                'browse_groups' => ['Browse Groups', 'Allow browsing of groups'],
                'create_group' => ['Create Group', 'Allow creation of group'],
                'delete_group' => ['Delete Group', 'Allow deletion group'],
                'edit_group' => ['Edit Group', 'Allow editing of group']
            ]
        ],

        [
            'name' => 'category',
            'items' => [
                'browse_categories' => ['Browse Categories', 'Allow browsing of categories'],
                'create_category' => ['Create Category', 'Allow creation of category'],
                'delete_category' => ['Delete Category', 'Allow deletion category'],
                'edit_category' => ['Edit Category', 'Allow editing of category']
            ]
        ],

        [
            'name' => 'supplier',
            'items' => [
                'browse_suppliers' => ['Browse Suppliers', 'Allow browsing of suppliers'],
                'create_supplier' => ['Create Supplier', 'Allow creation of supplier'],
                'delete_supplier' => ['Delete Supplier', 'Allow deletion supplier'],
                'edit_supplier' => ['Edit Supplier', 'Allow editing of supplier']
            ]
        ],

        [
            'name' => 'product',
            'items' => [
                'browse_products' => ['Browse Products', 'Allow browsing of products'],
                'create_product' => ['Create Product', 'Allow creation of product'],
                'delete_product' => ['Delete Product', 'Allow deletion product'],
                'edit_product' => ['Edit Product', 'Allow editing of product']
            ]
        ],

        [
            'name' => 'user',
            'items' => [
                'browse_users' => ['Browse Users', 'Allow browsing of users'],
                'create_user' => ['Create User', 'Allow creation of user'],
                'delete_user' => ['Delete User', 'Allow deletion user'],
                'edit_user' => ['Edit User', 'Allow editing of user']
            ]
        ],

        [
            'name' => 'coupon',
            'items' => [
                'browse_coupons' => ['Browse Coupons', 'Allow browsing of coupons'],
                'create_coupon' => ['Create Coupon', 'Allow creation of coupon'],
                'delete_coupon' => ['Delete Coupon', 'Allow deletion coupon'],
                'edit_coupon' => ['Edit Coupon', 'Allow editing of coupon']
            ]
        ],

        [
            'name' => 'inventory',
            'items' => [
                'browse_inventories' => ['Browse Categories', 'Allow browsing of inventories'],
                'create_inventory' => ['Create Category', 'Allow creation of inventory'],
                'delete_inventory' => ['Delete Category', 'Allow deletion inventory'],
                'edit_inventory' => ['Edit Category', 'Allow editing of inventory']
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];

        foreach ($this->data as $group) {
            foreach ($group['items'] as $id => $entry) {
                $data[] = [
                    'id'            => $id,
                    'name'          => $entry[0],
                    'category'      => $group['name'],
                    'description'   => $entry[1]
                ];
            }
        }

        Permission::insert($data);
    }
}