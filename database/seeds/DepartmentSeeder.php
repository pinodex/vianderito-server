<?php

use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    private $departments = [
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
        foreach ($this->departments as $department) {
            Department::create($department);
        }
    }
}
