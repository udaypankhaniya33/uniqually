<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormWizardProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_wizard_products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('form_wizard_id');
            $table->unsignedBigInteger('product_entity_location_price_id');
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
        Schema::dropIfExists('form_wizard_products');
    }
}
