<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('order_created_by');
            $table->string('order_creator_location', 255)->nullable();
            $table->decimal('net_value')->default(0.00);
            $table->unsignedBigInteger('base_package_id');
            $table->timestamps();
            $table->foreign('order_created_by')->references('id')->on('users');
            $table->foreign('base_package_id')->references('id')->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
