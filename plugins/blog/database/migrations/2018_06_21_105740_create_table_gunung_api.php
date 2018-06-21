<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGunungApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mountains', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name', 100);
            $table->string('mountain_status', 15)->nullable();
            $table->dateTime('date_of_the_incident')->nullable();
            $table->longText('notes')->nullable();
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
        Schema::dropIfExists('mountains');
    }
}
