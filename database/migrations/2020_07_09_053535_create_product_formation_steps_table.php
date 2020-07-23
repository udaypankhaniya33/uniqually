<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductFormationStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_formation_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('product_entity_location_price_id');
            $table->unsignedBigInteger('formation_step_id');
            $table->integer('step');
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
        Schema::dropIfExists('product_formation_steps');
    }
}
