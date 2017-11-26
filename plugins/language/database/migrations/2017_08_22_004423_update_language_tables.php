<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLanguageTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->renameColumn('id', 'lang_id');
            $table->renameColumn('name', 'lang_name');
            $table->renameColumn('locale', 'lang_locale');
            $table->renameColumn('code', 'lang_code');
            $table->renameColumn('flag', 'lang_flag');
            $table->renameColumn('is_default', 'lang_is_default');
            $table->renameColumn('order', 'lang_order');
            $table->renameColumn('is_rtl', 'lang_is_rtl');
            $table->dropTimestamps();
        });

        Schema::table('language_meta', function (Blueprint $table) {
            $table->renameColumn('id', 'lang_meta_id');
            $table->renameColumn('content_id', 'lang_meta_content_id');
            $table->renameColumn('code', 'lang_meta_code');
            $table->renameColumn('reference', 'lang_meta_reference');
            $table->renameColumn('origin', 'lang_meta_origin');
            $table->dropTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('languages', function (Blueprint $table) {
            $table->renameColumn('lang_id', 'id');
            $table->renameColumn('lang_name', 'name');
            $table->renameColumn('lang_locale', 'locale');
            $table->renameColumn('lang_code', 'code');
            $table->renameColumn('lang_flag', 'flag');
            $table->renameColumn('lang_is_default', 'is_default');
            $table->renameColumn('lang_order', 'order');
            $table->renameColumn('lang_is_rtl', 'is_rtl');
            $table->timestamps();
        });

        Schema::table('language_meta', function (Blueprint $table) {
            $table->renameColumn('lang_meta_id', 'id');
            $table->renameColumn('lang_meta_content_id', 'content_id');
            $table->renameColumn('lang_meta_code', 'code');
            $table->renameColumn('lang_meta_reference', 'reference');
            $table->renameColumn('lang_meta_origin', 'origin');
            $table->timestamps();
        });
    }
}
