<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableFieldGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('field_groups', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('field_groups', function (Blueprint $table) {
            $table->tinyInteger('status')->unsigned()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('field_groups', function (Blueprint $table) {
            $table->enum('status', ['activated', 'disabled']);
        });
    }
}
