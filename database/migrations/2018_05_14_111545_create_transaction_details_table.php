<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('transaction_id');
            $table->foreign('transaction_id')->references('id')->on('transactions');
            $table->uuid('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->uuid('merchant_id');
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->uuid('currency_id');
            $table->foreign('currency_id')->references('id')->on('master_currencies');
            $table->integer('qty');
            $table->decimal('price', 18, 2);
            $table->string('point_type')->nullable();
            $table->uuid('point_type_id')->nullable();
            $table->decimal('point_value', 18, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_details');
    }
}
