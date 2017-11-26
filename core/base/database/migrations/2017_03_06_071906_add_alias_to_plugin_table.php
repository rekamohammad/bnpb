<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAliasToPluginTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->string('alias', 120);
            $table->string('author', 255)->nullable()->change();
            $table->string('url', 255)->nullable()->change();
            $table->string('version', 30)->nullable()->change();
            $table->string('description', 255)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plugins', function (Blueprint $table) {
            $table->dropColumn('alias');
        });
    }
}
