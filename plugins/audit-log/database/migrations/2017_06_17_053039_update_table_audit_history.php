<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateTableAuditHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audit_history', function (Blueprint $table) {
            $table->dropColumn('user_agent');
        });

        Schema::table('audit_history', function (Blueprint $table) {
            $table->text('user_agent')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audit_history', function (Blueprint $table) {
            $table->dropColumn('user_agent');
        });

        Schema::table('audit_history', function (Blueprint $table) {
            $table->string('user_agent', 255)->nullable();
        });
    }
}
