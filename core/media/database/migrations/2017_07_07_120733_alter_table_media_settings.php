<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableMediaSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('media_settings', function (Blueprint $table) {
            $table->dropColumn('user_id');
            $table->integer('media_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media_settings', function (Blueprint $table) {
            $table->dropColumn('media_id');
            $table->integer('user_id')->references('id')->on('users')->index();
        });
    }
}
