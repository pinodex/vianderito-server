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
            'name' => 'John Doe',
            'username' => 'johndoe',
            'password' => 'password',
            'phone_number' => '09551234567',
            'is_verified' => true
        ]);

        User::create([
            'name' => 'Juan Dela Cruz',
            'username' => 'juandelacruz',
            'password' => 'password',
            'phone_number' => '09995551111',
            'is_verified' => true
        ]);

        User::create([
            'name' => 'Jane Smith',
            'username' => 'janesmith',
            'password' => 'password',
            'phone_number' => '09759994545',
            'is_verified' => true
        ]);
    }
}
