<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('transaction_code')->unique();
            $table->string('customer_name');
            $table->uuid('customer_id');
            $table->uuid('currency_id');
            $table->foreign('currency_id')->references('id')->on('master_currencies');
            $table->decimal('price', 18, 2);
            $table->string('point_type')->nullable();
            $table->uuid('point_type_id')->nullable();
            $table->decimal('point_value', 18, 2)->default(0);
            $table->uuid('payment_id');
            $table->enum('status', ['Pending', 'Settlement', 'Success', 'Void']);
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
        Schema::dropIfExists('transactions');
    }
}
