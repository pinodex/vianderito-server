<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path('data/categories.csv');
        $handle = fopen($path, 'r');
        $row = -1;

        $data = [];

        while ($line = fgetcsv($handle)) {
            $row++;

            if ($row == 0) {
                continue;
            }

            Category::create([
                'name' => $line[0]
            ]);
        }
    }
}
