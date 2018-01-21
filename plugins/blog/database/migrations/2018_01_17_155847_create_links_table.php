<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Links', function (Blueprint $table) {
			$table->increments('id');
			$table->string('categories',25);
			$table->Integer('province')->unsigned()->default(0);
			$table->string('kabupaten',200)->nullable();
			$table->string('name',200)->nullable();
			$table->text('url')->nullable();
			$table->text('address')->nullable();
			$table->tinyInteger('status')->unsigned()->default(1);
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
        Schema::dropIfExists('links');
    }
}
