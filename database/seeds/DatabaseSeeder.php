<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PermissionSeeder::class);
        $this->call(DepartmentSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(DepartmentPermissionSeeder::class);

        $this->call(CategorySeeder::class);
        $this->call(SupplierSeeder::class);
        $this->call(ProductSeeder::class);
        $this->call(InventorySeeder::class);
        $this->call(CouponSeeder::class);

        $this->call(UserSeeder::class);

        $this->call(InventoryLossSeeder::class);
        $this->call(TransactionSeeder::class);
        $this->call(TransactionInventorySeeder::class);
        $this->call(PurchaseSeeder::class);
        $this->call(PaymentSeeder::class);
    }
}
