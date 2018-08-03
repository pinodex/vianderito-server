<?php

use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    protected $manufacturerMap = [];

    protected $categoryMap = [];

    protected function getManufacturerId($name)
    {
        if (array_key_exists($name, $this->manufacturerMap)) {
            return $this->manufacturerMap[$name];
        }

        $query = DB::table('manufacturers')->where('name', $name)->first();

        if ($query) {
            return $this->manufacturerMap[$name] = $query->id;
        }

        return null;
    }

    protected function getCategoryId($name)
    {
        if (array_key_exists($name, $this->categoryMap)) {
            return $this->categoryMap[$name];
        }

        $query = DB::table('categories')->where('name', $name)->first();

        if ($query) {
            return $this->categoryMap[$name] = $query->id;
        }

        return null;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = $GLOBALS['time'];

        $path = storage_path('data/products.csv');
        $handle = fopen($path, 'r');
        $row = -1;

        $data = [];

        while ($line = fgetcsv($handle)) {
            $row++;

            if ($row == 0) {
                continue;
            }

            $data[] = [
                'id'                => (string) Uuid::generate(),
                'manufacturer_id'   => $this->getManufacturerId($line[3]),
                'category_id'       => $this->getCategoryId($line[2]),
                'name'              => $line[0],
                'upc'               => $line[1],
                'created_at'        => $time,
                'updated_at'        => $time
            ];
        }

        DB::table('products')->insert($data);
    }
}
