<?php

Route::group(['namespace' => 'Botble\Translation\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'system/translations', 'permission' => false], function () {

            Route::get('view/{group?}', [
                'as' => 'translations.group.view',
                'uses' => 'TranslationController@getView',
            ]);

            Route::get('/{group?}', [
                'as' => 'translations.list',
                'uses' => 'TranslationController@getIndex',
            ]);

            Route::post('edit', [
                'as' => 'translations.group.edit',
                'uses' => 'TranslationController@postEdit',
            ]);

            Route::post('add', [
                'as' => 'translations.group.add',
                'uses' => 'TranslationController@postAdd',
            ]);

            Route::post('/delete', [
                'as' => 'translations.group.delete',
                'uses' => 'TranslationController@postDelete',
            ]);

            Route::post('/publish', [
                'as' => 'translations.group.publish',
                'uses' => 'TranslationController@postPublish',
            ]);

            Route::post('/import', [
                'as' => 'translations.import',
                'uses' => 'TranslationController@postImport',
            ]);

            Route::post('/find', [
                'as' => 'translations.find',
                'uses' => 'TranslationController@postFind',
            ]);
        });
    });

});