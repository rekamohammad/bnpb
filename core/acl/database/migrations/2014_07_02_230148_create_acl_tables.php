<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAclTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('activations');
        Schema::dropIfExists('persistences');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_users');
        Schema::dropIfExists('throttle');
        Schema::dropIfExists('users');
        Schema::dropIfExists('role_flags');
        Schema::dropIfExists('features');
        Schema::dropIfExists('permission_flags');
        Schema::dropIfExists('invites');
        Schema::dropIfExists('user_meta');

        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('email', 60)->unique();
            $table->string('password', 60)->nullable();
            $table->text('permissions')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('username', 60)->unique()->nullable();
            $table->dateTime('dob')->nullable();
            $table->string('address', 255)->nullable();
            $table->string('secondary_address', 255)->nullable();
            $table->string('job_position', 60)->nullable();
            $table->string('phone', 15)->nullable();
            $table->string('secondary_phone', 15)->nullable();
            $table->string('secondary_email', 60)->nullable();
            $table->tinyInteger('gender')->nullable();
            $table->string('website', 120)->nullable();
            $table->string('skype', 60)->nullable();
            $table->string('facebook', 120)->nullable();
            $table->string('twitter', 120)->nullable();
            $table->string('google_plus', 120)->nullable();
            $table->string('youtube', 120)->nullable();
            $table->string('github', 120)->nullable();
            $table->string('interest', 255)->nullable();
            $table->string('about', 400)->nullable();
            $table->string('profile_image', 255)->nullable();
            $table->boolean('super_user')->default(0);
            $table->boolean('manage_supers')->default(0);
            $table->boolean('completed_profile')->default(0);
            $table->timestamps();

            $table->engine = 'InnoDB';
        });

        Schema::create('activations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('code', 120);
            $table->boolean('completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });

        Schema::create('persistences', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('code', 120)->unique();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });

        Schema::create('reminders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('code', 120);
            $table->boolean('completed')->default(0);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('roles', function (Blueprint $table) {
            $table->increments('id');
            $table->string('slug', 120)->unique();
            $table->string('name', 120);
            $table->text('permissions')->nullable();
            $table->string('description', 255);
            $table->tinyInteger('is_staff');
            $table->tinyInteger('is_default')->unsigned()->default(0);
            $table->integer('created_by')->unsigned()->references('id')->on('users')->index();
            $table->integer('updated_by')->unsigned()->references('id')->on('users')->index();
            $table->softDeletes();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });

        Schema::create('role_users', function (Blueprint $table) {
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->integer('role_id')->unsigned()->references('id')->on('roles')->index();
            $table->nullableTimestamps();

            $table->engine = 'InnoDB';
            $table->primary(['user_id', 'role_id']);
        });

        Schema::create('throttle', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->unsigned()->references('id')->on('users')->index();
            $table->string('type', 60);
            $table->string('ip')->nullable();
            $table->timestamps();

            $table->engine = 'InnoDB';
        });

        Schema::create('permission_flags', function ($table) {
            $table->increments('id');
            $table->string('flag', 100)->unique();
            $table->string('name', 100);
            $table->integer('parent_flag')->default(0);
            $table->integer('is_feature')->default(0);
            $table->integer('feature_visible')->default(1);
            $table->integer('permission_visible')->default(1);

            $table->engine = 'InnoDB';
            $table->timestamps();
        });

        Schema::create('role_flags', function ($table) {
            $table->integer('role_id')->references('id')->on('roles')->unsigned()->index();
            $table->integer('flag_id')->unsigned()->references('id')->on('permission_flags')->index();

            $table->engine = 'InnoDB';
        });

        Schema::create('features', function ($table) {
            $table->increments('id');
            $table->integer('feature_id')->references('id')->on('permission_flags')->unsigned()->index();
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('invites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token', 128);
            $table->boolean('accepted')->default(false);
            $table->integer('user_id');
            $table->integer('invitee_id');
            $table->integer('role_id');
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('user_meta', function ($table) {

            $table->increments('id');
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();

            $table->engine = 'InnoDB';
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
        Schema::dropIfExists('activations');
        Schema::dropIfExists('persistences');
        Schema::dropIfExists('reminders');
        Schema::dropIfExists('roles');
        Schema::dropIfExists('role_users');
        Schema::dropIfExists('throttle');
        Schema::dropIfExists('users');
        Schema::dropIfExists('role_flags');
        Schema::dropIfExists('features');
        Schema::dropIfExists('permission_flags');
        Schema::dropIfExists('invites');
        Schema::dropIfExists('user_meta');
    }
}
