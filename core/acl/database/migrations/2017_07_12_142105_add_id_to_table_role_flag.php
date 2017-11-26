<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIdToTableRoleFlag extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('role_flags', function (Blueprint $table) {
            $table->increments('id');
        });

        Schema::table('role_users', function (Blueprint $table) {
            $table->dropPrimary(['user_id', 'role_id']);
        });

        Schema::table('role_users', function (Blueprint $table) {
            $table->increments('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('role_flags', function (Blueprint $table) {
            $table->dropColumn('id');
        });

        Schema::table('role_users', function (Blueprint $table) {
            $table->dropColumn('id');
        });
    }
}
