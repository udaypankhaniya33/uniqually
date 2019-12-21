<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterUsersTableForSocialAuthentication extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('remember_token', 255)->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->boolean('is_social_auth')->after('remember_token')->nullable();
        });
    }
}
