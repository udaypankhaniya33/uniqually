<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDiscountedPriceAndPackageIdColumnsToPackageAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_addons', function (Blueprint $table) {
            $table->decimal('discounted_price')->default(0.00)
                ->after('price')->nullable();
            $table->unsignedBigInteger('package_id');
            $table->foreign('package_id')->references('id')->on('packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('package_addons', function (Blueprint $table) {
            $table->dropColumn(['discounted_price']);
            $table->dropColumn(['package_id']);
        });
    }
}
