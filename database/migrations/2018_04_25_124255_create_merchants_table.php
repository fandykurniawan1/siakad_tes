<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMerchantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('parent_id')->nullable();
            $table->string('code');
            $table->string('name');
            $table->enum('type', ['Default', 'Money Changer', 'Outlet'])->default('Default');

            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->uuid('country_id')->nullable();
            $table->foreign('country_id')->references('id')->on('master_countries');
            $table->uuid('province_id')->nullable();
            $table->foreign('province_id')->references('id')->on('master_provinces');
            $table->uuid('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('master_cities');
            $table->decimal('longitude', 9, 6)->nullable()->comment('-180 until 180');
            $table->decimal('latitude', 8, 6)->nullable()->comment('-90 until 90');

            $table->string('activation_code')->nullable();
            $table->enum('status', ['Active', 'Not Verified', 'Not Active', 'Blacklisted']);
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
        Schema::dropIfExists('merchants');
    }
}
