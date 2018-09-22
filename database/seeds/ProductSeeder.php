<?php

use App\Models\Product;

class ProductSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = storage_path('data/products.csv');
        $handle = fopen($path, 'r');
        $row = -1;

        $data = [];

        while ($line = fgetcsv($handle)) {
            $row++;

            if ($row == 0) {
                continue;
            }

            Product::create([
                'supplier_id'   => $this->getSupplierId($line[3]),
                'category_id'       => $this->getCategoryId($line[2]),
                'name'              => $line[0],
                'upc'               => $line[1]
            ]);
        }
    }
}
