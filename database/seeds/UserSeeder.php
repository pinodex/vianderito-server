<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Sample User',
            'username' => 'user',
            'password' => 'password',
            'is_verified' => true
        ]);
    }
}
