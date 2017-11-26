<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePostsTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 120);
            $table->string('slug', 120);
            $table->string('description', 400);
            $table->text('content');
            $table->tinyInteger('status')->unsigned()->default(1);
            $table->integer('user_id')->references('id')->on('users');
            $table->tinyInteger('featured')->unsigned()->default(0);
            $table->string('image', 255)->nullable();
            $table->integer('views')->unsigned()->default(0);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tag_id')->unsigned()->index()->references('id')->on('tags')->onDelete('cascade');
            $table->integer('post_id')->unsigned()->index()->references('id')->on('posts')->onDelete('cascade');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('post_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('category_id')->unsigned()->index()->references('id')->on('categories')->onDelete('cascade');
            $table->integer('post_id')->unsigned()->index()->references('id')->on('posts')->onDelete('cascade');
            $table->softDeletes();
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
        Schema::dropIfExists('posts');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('post_category');
    }

}
