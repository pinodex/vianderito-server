<?php

use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    protected $productMap = [];

    protected function getProductId($upc)
    {
        if (array_key_exists($upc, $this->productMap)) {
            return $this->productMap[$upc];
        }

        $query = DB::table('products')->where('upc', $upc)->first();

        if ($query) {
            return $this->productMap[$upc] = $query->id;
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

        $path = storage_path('data/inventories.csv');
        $handle = fopen($path, 'r');
        $row = -1;

        $data = [];
        $eid = 100;

        while ($line = fgetcsv($handle)) {
            $row++;

            if ($row == 0) {
                continue;
            }

            $data[] = [
                'id'                => (string) Uuid::generate(),
                'eid'               => $eid,
                'product_id'        => $this->getProductId($line[0]),
                'stocks'            => $line[1],
                'price'             => $line[2],
                'batch_date'        => $line[3],
                'expiration_date'   => $line[4],
                'created_at'        => $time,
                'updated_at'        => $time
            ];

            $eid++;
        }

        DB::table('inventories')->insert($data);
    }
}
