<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    protected $data = [
        [
            'name' => 'account',
            'items' => [
                'browse_accounts' => ['Browse Accounts', 'Allow browsing of accounts'],
                'create_account' => ['Create Account', 'Allow creation of account'],
                'delete_account' => ['Delete Account', 'Allow deletion account'],
                'edit_account' => ['Edit Account', 'Allow editing of account']
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
            'name' => 'manufacturer',
            'items' => [
                'browse_manufacturers' => ['Browse Manufacturers', 'Allow browsing of manufacturers'],
                'create_manufacturer' => ['Create Manufacturer', 'Allow creation of manufacturer'],
                'delete_manufacturer' => ['Delete Manufacturer', 'Allow deletion manufacturer'],
                'edit_manufacturer' => ['Edit Manufacturer', 'Allow editing of manufacturer']
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
        $time = $GLOBALS['time'];

        // Flatten data
        foreach ($this->data as $group) {
            foreach ($group['items'] as $id => $entry) {
                $data[] = [
                    'id'            => $id,
                    'name'          => $entry[0],
                    'category'      => $group['name'],
                    'description'   => $entry[1],
                    'created_at'    => $time,
                    'updated_at'    => $time
                ];
            }
        }

        DB::table('permissions')->insert($data);
    }
}