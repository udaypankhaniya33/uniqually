<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaximumQuantityColumnToPackageAddonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('package_addons', function (Blueprint $table) {
            $table->integer('maximum_quantity')->default(1)
                ->after('description')->nullable();
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
            $table->dropColumn(['maximum_quantity']);
        });
    }
}
