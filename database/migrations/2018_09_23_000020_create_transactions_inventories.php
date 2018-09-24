<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsInventories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions_inventories', function (Blueprint $table) {
            $table->uuid('transaction_id');
            $table->uuid('inventory_id');

            $table->integer('quantity');

            $table->primary(['transaction_id', 'inventory_id']);

            $table->foreign('transaction_id')->references('id')->on('transactions')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->foreign('inventory_id')->references('id')->on('inventories')
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
        Schema::dropIfExists('transactions_inventories');
    }
}
