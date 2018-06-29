<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('product_id')->index();
            $table->foreign('product_id')->references('id')->on('products');
            $table->uuid('merchant_id')->nullable()->index();
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->enum('type', ['in', 'out', 'return']);
            $table->integer('qty');
            $table->integer('current_stock');
            $table->string('serial_no')->nullable();
            $table->uuid('user_by')->nullable();
            $table->foreign('user_by')->references('id')->on('users');
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
        Schema::dropIfExists('product_logs');
    }
}
