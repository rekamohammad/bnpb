<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMetaBoxesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_boxes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->unsigned()->index();
            $table->string('meta_key', 255);
            $table->text('meta_value')->nullable();
            $table->string('reference', 120);
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
        Schema::dropIfExists('meta_boxes');
    }
}
