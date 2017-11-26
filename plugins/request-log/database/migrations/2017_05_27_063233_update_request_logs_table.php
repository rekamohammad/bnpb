<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRequestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_logs', function (Blueprint $table) {
            $table->dropColumn('referer');
        });

        Schema::table('request_logs', function (Blueprint $table) {
            $table->text('referrer')->after('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_logs', function (Blueprint $table) {
            $table->dropColumn('referrer');
        });

        Schema::table('request_logs', function (Blueprint $table) {
            $table->text('referer')->after('user_id')->nullable();
        });
    }
}
