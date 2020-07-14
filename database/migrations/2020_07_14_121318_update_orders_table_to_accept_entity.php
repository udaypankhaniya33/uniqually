<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdersTableToAcceptEntity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_entity_order')->after('order_status')->default(0);
            $table->unsignedBigInteger('product_entity_location_price_id')->after('is_entity_order')->nullable();
            $table->unsignedBigInteger('base_package_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['is_entity_order', 'product_entity_location_price_id']);
        });
    }
}
