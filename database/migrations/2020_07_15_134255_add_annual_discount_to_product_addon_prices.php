<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAnnualDiscountToProductAddonPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_addon_prices', function (Blueprint $table) {
            $table->double('annual_discount')->after('price')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_addon_prices', function (Blueprint $table) {
            $table->dropColumn('annual_discount');
        });
    }
}
