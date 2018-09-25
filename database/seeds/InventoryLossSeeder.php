<?php

use Illuminate\Database\Seeder;
use App\Models\InventoryLoss;
use App\Models\Inventory;
use Carbon\Carbon;

class InventoryLossSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $randomInventories = Inventory::inRandomOrder()->take(10)->get();

        $randomInventories->each(function (Inventory $inventory) {
            $lossCount = rand(1, 3);
            $startingDate = Carbon::parse($inventory->batch_date);

            for ($i = 1; $i <= $lossCount; $i++) {
                $units = rand(1, 10);
                $date = $startingDate->addDays(rand(1, 10));

                $inventory->losses()->create([
                    'units' => $units,
                    'remarks' => 'Sample loss remark #' . $i,
                    'updated_at' => $date,
                    'created_at' => $date
                ]);
            }
        });
    }
}
