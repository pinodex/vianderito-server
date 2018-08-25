<?php

use App\Models\Inventory;

class InventorySeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
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

            Inventory::create([
                'eid'               => $eid,
                'product_id'        => $this->getProductId($line[0]),
                'stocks'            => $line[1],
                'price'             => $line[2],
                'batch_date'        => $line[3],
                'expiration_date'   => $line[4]
            ]);

            $eid++;
        }
    }
}
