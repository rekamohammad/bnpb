<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCategoriesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 120);
            $table->string('slug', 120);
            $table->integer('parent_id')->unsigned()->default(0)->index();
            $table->text('description');
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->integer('user_id')->unsigned()->index()->references('id')->on('users');
            $table->softDeletes();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }

}
