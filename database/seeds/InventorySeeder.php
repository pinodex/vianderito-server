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
                'quantity'          => $line[1],
                'cost'              => $line[2],
                'price'             => $line[3],
                'batch_date'        => $line[4],
                'expiration_date'   => $line[5]
            ]);

            $eid++;
        }
    }
}
