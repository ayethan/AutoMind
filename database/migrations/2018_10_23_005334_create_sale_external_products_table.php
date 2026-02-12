<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaleExternalProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sale_external_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->decimal('buy_price', 12 , 0);
            $table->decimal('sell_price', 12 , 0);
            $table->integer('qty')->default(1);
            $table->text('remark')->nullable();
            $table->unsignedInteger("sale_id"); 
            $table->timestamps();

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
        Schema::dropIfExists('sale_external_products');
    }
}
