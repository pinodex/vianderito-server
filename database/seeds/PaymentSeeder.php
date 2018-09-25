<?php

use Illuminate\Database\Seeder;
use App\Models\Purchase;
use App\Models\Payment;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Purchase::all()->each(function (Purchase $purchase) {
            $payment = new Payment();

            $payment->user_id = $purchase->user_id;
            $payment->transaction_id = $purchase->transaction_id;
            $payment->gateway_id = str_random(8);
            $payment->status = 'authorized';
            $payment->amount = $purchase->amount;
            $payment->created_at = $purchase->created_at;
            $payment->updated_at = $purchase->updated_at;

            $payment->save();
        });
    }
}
