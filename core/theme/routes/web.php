<?php

Route::group(['namespace' => 'Botble\Theme\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'theme'], function () {

            Route::get('/', [
                'as' => 'theme.list',
                'uses' => 'ThemeController@getList',
            ]);

            Route::get('/options', [
                'as' => 'theme.options',
                'uses' => 'ThemeController@getOptions',
            ]);

            Route::post('/options', [
                'as' => 'theme.options',
                'uses' => 'ThemeController@postUpdate',
            ]);

            Route::get('/active/{theme}', [
                'as' => 'theme.active',
                'uses' => 'ThemeController@getActiveTheme',
            ]);

        });
    });

});