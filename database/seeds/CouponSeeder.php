<?php

use App\Models\Coupon;

class CouponSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $time = $GLOBALS['time'];

        $path = storage_path('data/coupons.csv');
        $handle = fopen($path, 'r');
        $row = -1;

        while ($line = fgetcsv($handle)) {
            $row++;

            if ($row == 0) {
                continue;
            }

            $entry = [
                'id'                        => (string) Uuid::generate(),
                'code'                      => $line[0],
                'description'               => $line[1],
                'discount_type'             => $line[2],
                'discount_price'            => $line[3],
                'discount_percentage'       => $line[4],
                'discount_floor_price'      => $line[5],
                'discount_ceiling_price'    => $line[6],
                'validity_start'            => $line[7],
                'validity_end'              => $line[8]
            ];

            $model = Coupon::create($entry);
            
            $categories = [];
            $inventories = [];
            $manufacturers = [];
            $products = [];

            foreach (explode(' ', $line[9]) as $productUpc) {
                if ($id = $this->getProductId($productUpc)) {
                    $products[] = $id;
                }
            }

            foreach (explode(' ', $line[10]) as $manufacturerCode) {
                if ($id = $this->getManufacturerId($manufacturerCode)) {
                    $manufacturers[] = $id;
                }
            }

            $model->products()->attach($products);
            $model->manufacturers()->attach($manufacturers);
        }
    }
}
