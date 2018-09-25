<?php

use Illuminate\Database\Seeder;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        for ($i = 0; $i < 10; $i++) {
            $date = $now->subDays(rand(1, 10));

            Transaction::create([
                'status' => 'complete',
                'updated_at' => $date,
                'created_at' => $date
            ]);
        }
    }
}
