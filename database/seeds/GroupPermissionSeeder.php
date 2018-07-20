<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $id = $GLOBALS['admin_group_id'];

        $permissions = DB::table('permissions')->get()->pluck('id')
            ->map(function ($permission) use ($id) {
                return [
                    'group_id'      => (string) $id,
                    'permission_id' => $permission
                ];
            })
            ->toArray();

        DB::table('groups_permissions')->insert($permissions);
    }
}
