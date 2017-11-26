<?php

use Illuminate\Database\Migrations\Migration;

class CreateAuditHistory extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('audit_history', function ($table) {
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('module', 60)->index();
            $table->text('request')->nullable();
            $table->string('action', 120);
            $table->string('user_agent', 255)->nullable();
            $table->string('ip_address', 25)->nullable();
            $table->integer('reference_user')->unsigned();
            $table->integer('reference_id')->unsigned();
            $table->string('reference_name', 255);
            $table->string('type', 20);
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
        Schema::dropIfExists('audit_history');
    }

}
