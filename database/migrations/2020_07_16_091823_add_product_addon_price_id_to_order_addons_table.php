<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProductAddonPriceIdToOrderAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_addons', function (Blueprint $table) {
            $table->unsignedBigInteger('product_addon_price_id')->after('package_addon_id')->nullable();
            $table->unsignedBigInteger('package_addon_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_addons', function (Blueprint $table) {
            $table->dropColumn('product_addon_price_id');
        });
    }
}
