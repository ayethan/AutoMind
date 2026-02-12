<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_taxi')->default(0);
            $table->boolean('is_paid')->default(0);
            $table->date('date');
            $table->decimal('advance', 12, 0)->default(0);
            $table->decimal('discount', 12 ,0)->default(0);
            $table->text('remark')->nullable();
            $table->string('job_done_by')->nullable();
            $table->string('mileage')->nullable();

            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('customer_id')->nullable();
            $table->unsignedInteger('car_id')->nullable();

            $table->integer('status')->default(1)->comment('refer to tinyerp.sale-status');
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('car_id')->references('id')->on('cars')->onDelte('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales');
    }
}
