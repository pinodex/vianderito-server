<?php

use Illuminate\Database\Seeder;

class ManufacturerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = $GLOBALS['time'];

        $path = storage_path('data/manufacturers.csv');
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
                'code'          => $line[1],
                'created_at'    => $time,
                'updated_at'    => $time
            ];
        }

        DB::table('manufacturers')->insert($data);
    }
}
