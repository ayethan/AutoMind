<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_services', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 12 ,0);
            $table->integer('qty')->default(1);
            $table->text("remark")->nullable();
            $table->unsignedInteger('service_id')->nullable();
            $table->unsignedInteger('sale_id');
            $table->timestamps();

            $table->foreign('service_id')->references('id')->on('services')->onDelete('set null');
            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sale_services');
    }
}
