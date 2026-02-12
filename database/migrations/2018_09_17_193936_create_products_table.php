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
            $table->increments('id');
            $table->string('name');
            $table->string('code')->unique();
            $table->unsignedInteger('stock')->default(0);
            $table->decimal('buy_price', 12 , 0);
            $table->decimal('sell_price', 12 , 0)->nullable();
            $table->text('remark')->nullable();
            $table->softDeletes();
            $table->unsignedInteger("category_id")->nullable();
            $table->unsignedInteger("sub_category_id")->nullable();
            $table->timestamps();

            $table->foreign("category_id")->references("id")->on("categories")->onDelete("set null");
            $table->foreign("sub_category_id")->references("id")->on("sub_categories")->onDelete("set null");
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
