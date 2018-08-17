<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsManufacturersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons_manufacturers', function (Blueprint $table) {
            $table->uuid('coupon_id');
            $table->uuid('manufacturer_id');

            $table->primary(['coupon_id', 'manufacturer_id']);

            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('manufacturer_id')->references('id')->on('manufacturers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons_manufacturers');
    }
}
