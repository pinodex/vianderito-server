<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webpatser\Uuid\Uuid;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = $GLOBALS['time'];

        $path = storage_path('data/categories.csv');
        $handle = fopen($path, 'r');
        $row = -1;

        $data = [];

        while ($line = fgetcsv($handle)) {
            $row++;

            if ($row == 0) {
                continue;
            }

            $data[] = [
                'id'            => (string) Uuid::generate(),
                'name'          => $line[0],
                'description'   => '',
                'created_at'    => $time,
                'updated_at'    => $time
            ];
        }

        DB::table('categories')->insert($data);
    }
}
