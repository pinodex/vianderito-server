<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = $GLOBALS['time'];
        $adminId = $GLOBALS['admin_account_id'];
        $groupAdminId = $GLOBALS['admin_group_id'];

        DB::table('accounts')->insert([
            'id'            => $adminId,
            'group_id'      => $groupAdminId,
            'first_name'    => 'Admin',
            'last_name'     => 'Admin',
            'username'      => 'admin',
            'password'      => bcrypt('admin'),
            'require_password_change'   => 0,
            'created_at'    => $time,
            'updated_at'    => $time
        ]);
    }
}
