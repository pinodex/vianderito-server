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

        $data = collect([]);

        while ($line = fgetcsv($handle)) {
            $row++;

            if ($row == 0) {
                continue;
            }

            $data->push([
                'category'      => $line[0],
                'subcategory'   => $line[1]
            ]);
        }

        $categories = $data->groupBy('category');
        $normalizedData = [];

        $categories->each(function ($item, $key) use (&$normalizedData, $time) {
            $parentId = (string) Uuid::generate();

            $normalizedData[] = [
                'id' => $parentId,
                'parent_id' => null,
                'name' => $key,
                'description' => '',
                'created_at'    => $time,
                'updated_at'    => $time
            ];

            $item->each(function ($subItem) use (&$normalizedData, $parentId, $time) {
                $normalizedData[] = [
                    'id' => (string) Uuid::generate(),
                    'parent_id' => $parentId,
                    'name' => $subItem['subcategory'],
                    'description' => '',
                    'created_at'    => $time,
                    'updated_at'    => $time
                ];
            });
        });

        DB::table('categories')->insert($normalizedData);
    }
}
