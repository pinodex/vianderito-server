<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsSuppliersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons_suppliers', function (Blueprint $table) {
            $table->uuid('coupon_id');
            $table->uuid('supplier_id');

            $table->primary(['coupon_id', 'supplier_id']);

            $table->foreign('coupon_id')->references('id')->on('coupons')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('supplier_id')->references('id')->on('suppliers')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons_suppliers');
    }
}
