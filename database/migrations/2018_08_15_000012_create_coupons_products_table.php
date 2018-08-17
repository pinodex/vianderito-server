<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons_products', function (Blueprint $table) {
            $table->uuid('coupon_id');
            $table->uuid('product_id');

            $table->primary(['coupon_id', 'product_id']);

            $table->foreign('coupon_id')->references('id')->on('coupons');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons_products');
    }
}
