<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use App\Models\Purchase;
use App\Models\User;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        Transaction::all()->each(function (Transaction $transaction) {
            $user = User::inRandomOrder()->first();

            $transaction->moveToPurchases($user);
        });

        Purchase::all()->each(function (Purchase $purchase) use ($now) {
            $date = $now->subDays(rand(1, 10));

            $purchase->created_at = $date;
            $purchase->updated_at = $date;

            $purchase->save();
        });
    }
}
