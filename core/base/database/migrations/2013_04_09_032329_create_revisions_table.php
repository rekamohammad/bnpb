<?php

use Illuminate\Database\Migrations\Migration;

class CreateRevisionsVendorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revisions', function ($table) {
            $table->increments('id');
            $table->string('revisionable_type')->index();
            $table->integer('revisionable_id')->index();
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('key');
            $table->text('old_value', 65535)->nullable();
            $table->text('new_value', 65535)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('revisions');
    }
}
