<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKebencanaanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kebencanaan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 120);
            $table->string('type', 120);
            $table->text('content');
            $table->integer('user_id')->references('id')->on('users');
            $table->string('image', 255)->nullable();
            $table->integer('views')->unsigned()->default(0);
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
        Schema::dropIfExists('kebencanaan');
    }
}
