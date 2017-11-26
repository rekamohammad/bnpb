<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediaTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('media_folders');
        Schema::dropIfExists('media_files');
        Schema::dropIfExists('media_storage');
        Schema::dropIfExists('media_shares');
        Schema::dropIfExists('media_settings');

        Schema::create('media_folders', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->integer('parent_id')->default(0);
            $table->tinyInteger('is_public')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';

        });

        Schema::create('media_files', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('name', 255);
            $table->integer('folder_id')->default(0)->unsigned();
            $table->string('mime_type', 120);
            $table->integer('size');
            $table->string('url', 255);
            $table->text('options')->nullable();
            $table->tinyInteger('is_public')->default(0);
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';

        });

        Schema::create('media_shares', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->references('id')->on('users')->index();
            $table->integer('shared_by')->references('id')->on('users')->index();
            $table->integer('share_id')->default(0)->unsigned();
            $table->string('share_type');
            $table->timestamps();
            $table->softDeletes();
            $table->engine = 'InnoDB';
        });

        Schema::create('media_settings', function (Blueprint $table) {

            $table->increments('id');
            $table->string('key', 120);
            $table->text('value')->nullable();
            $table->integer('user_id')->references('id')->on('users')->index();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('personal_quota')->unsigned()->default(104857600);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_folders');
        Schema::dropIfExists('media_files');
        Schema::dropIfExists('media_shares');
        Schema::dropIfExists('media_settings');

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('personal_quota');
        });
    }
}
