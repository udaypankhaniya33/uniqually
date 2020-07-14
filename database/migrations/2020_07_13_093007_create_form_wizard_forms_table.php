<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormWizardFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_wizard_forms', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('form_wizard_id');
            $table->unsignedBigInteger('form_id');
            $table->integer('position');
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
        Schema::dropIfExists('form_wizard_forms');
    }
}
