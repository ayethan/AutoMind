<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->increments('id');
            $table->string('car_no')->unique();
            $table->string('model')->nullable();
            $table->unsignedInteger("car_made_id")->nullable();
            $table->unsignedInteger("customer_id")->nullable();
            $table->timestamps();

            $table->foreign("car_made_id")->references("id")->on("car_mades")->onDelete("set null");
            $table->foreign("customer_id")->references("id")->on("customers")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
}
