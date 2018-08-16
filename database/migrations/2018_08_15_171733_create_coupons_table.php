<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->string('code');
            $table->text('description')->nullable();

            $table->string('discount_type');
            $table->decimal('discount_price', 6, 2)->nullable();
            $table->decimal('discount_percentage', 5, 2)->nullable();

            $table->decimal('discount_floor_price', 6, 2)->nullable();
            $table->decimal('discount_ceiling_price', 6, 2)->nullable();

            $table->datetime('validity_start');
            $table->datetime('validity_end');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
