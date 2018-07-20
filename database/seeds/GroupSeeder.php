<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminId = $GLOBALS['admin_group_id'];
        $time = $GLOBALS['time'];

        DB::table('groups')->insert([
            'id'            => $adminId,
            'name'          => 'Administrator',
            'created_at'    => $time,
            'updated_at'    => $time
        ]);
    }
}
