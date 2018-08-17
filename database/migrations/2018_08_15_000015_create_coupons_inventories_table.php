<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons_inventories', function (Blueprint $table) {
            $table->uuid('coupon_id');
            $table->uuid('inventory_id');

            $table->primary(['coupon_id', 'inventory_id']);

            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('inventory_id')->references('id')->on('inventories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons_inventories');
    }
}
