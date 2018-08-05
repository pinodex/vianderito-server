<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => (string) Uuid::generate(),
            'name' => 'Sample User',
            'username' => 'user',
            'password' => bcrypt('password'),
            'is_verified' => true
        ]);
    }
}
