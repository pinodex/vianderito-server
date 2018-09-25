<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Inventory;

class TransactionInventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Transaction::all()->each(function (Transaction $transaction) {
            $itemCount = rand(1, 5);

            Inventory::inRandomOrder()->take($itemCount)
                ->get()->each(function (Inventory $inventory) use ($transaction) {
                    $quantity = rand(1, 5);

                    $transaction->inventories()->attach($inventory->id, [
                        'quantity' => $quantity
                    ]);
                });
        });
    }
}
