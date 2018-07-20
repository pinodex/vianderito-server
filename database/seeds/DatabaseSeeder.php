<?php

use Illuminate\Database\Seeder;
use Webpatser\Uuid\Uuid;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $GLOBALS['time'] = Carbon::now();
        $GLOBALS['admin_group_id'] = Uuid::generate();
        $GLOBALS['admin_account_id'] = Uuid::generate();

        $this->call(PermissionSeeder::class);
        $this->call(GroupSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(GroupPermissionSeeder::class);
    }
}
