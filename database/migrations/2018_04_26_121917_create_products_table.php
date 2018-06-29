<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->index();
            $table->string('name')->index();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('price', 18, 2);
            $table->decimal('value', 18, 2)->nullable()->default(0);
            $table->uuid('currency_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('master_currencies');
            $table->integer('stock');
            $table->uuid('brand_id')->nullable()->index();
            $table->foreign('brand_id')->references('id')->on('master_brands');
            $table->text('description')->nullable();
            $table->text('specification')->nullable();
            $table->enum('type', ['Product', 'Voucher', 'Bank Note'])->default('Product');
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('products');
    }
}
