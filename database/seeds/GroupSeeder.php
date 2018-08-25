<?php

use Illuminate\Database\Seeder;
use App\Models\Group;

class GroupSeeder extends Seeder
{
    private $groups = [
        ['name' => 'Administrator'],
        ['name' => 'Sales & Marketing'],
        ['name' => 'Inventory Manager'],
        ['name' => 'User Support'],
        ['name' => 'Audit']
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->groups as $group) {
            Group::create($group);
        }
    }
}
