<?php

use Illuminate\Database\Seeder;
use App\Models\Account;
use App\Models\Group;

class AccountSeeder extends Seeder
{
    private $accounts = [
        'Administrator' => [
            [
                'first_name' => 'Admin',
                'last_name' => 'Admin',
                'username' => 'admin',
                'password' => 'admin',
                'email' => 'admin@vianderito.xyz'
            ]
        ],

        'Sales & Marketing' => [
            [
                'first_name' => 'Sales',
                'last_name' => 'Sales',
                'username' => 'sales',
                'password' => 'sales',
                'email' => 'sales@vianderito.xyz'
            ]
        ],

        'Inventory Manager' => [
            [
                'first_name' => 'Inventory',
                'last_name' => 'Inventory',
                'username' => 'inventory',
                'password' => 'inventory',
                'email' => 'inventory@vianderito.xyz'
            ]
        ],

        'User Support' => [
            [
                'first_name' => 'Support',
                'last_name' => 'Support',
                'username' => 'support',
                'password' => 'support',
                'email' => 'support@vianderito.xyz'
            ]
        ],

        'Audit' => [
            [
                'first_name' => 'Auditor',
                'last_name' => 'Auditor',
                'username' => 'auditor',
                'password' => 'auditor',
                'email' => 'auditor@vianderito.xyz'
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
        foreach ($this->accounts as $groupName => $accounts) {
            $group = Group::where('name', $groupName)->first();

            foreach ($accounts as $account) {
                $account['require_password_change'] = false;

                $model = Account::create($account);

                $model->group()->associate($group);
                $model->save();
            }
        }
    }
}
