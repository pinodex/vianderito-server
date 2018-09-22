<?php

use Illuminate\Database\Seeder;
use App\Models\Supplier;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path('data/suppliers.csv');
        $handle = fopen($path, 'r');
        $row = -1;

        $data = [];

        while ($line = fgetcsv($handle)) {
            $row++;

            if ($row == 0) {
                continue;
            }

            Supplier::create([
                'name' => $line[0],
                'code' => $line[1]
            ]);
        }
    }
}
