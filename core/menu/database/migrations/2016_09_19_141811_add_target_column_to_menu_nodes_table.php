<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTargetColumnToMenuNodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->enum('target', ['_blank', '_parent', '_self', '_top'])->after('css_class')->default('_self');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menu_nodes', function (Blueprint $table) {
            $table->dropColumn('target');
        });
    }
}
