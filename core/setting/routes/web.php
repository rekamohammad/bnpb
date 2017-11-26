<?php

Route::group(['namespace' => 'Botble\Setting\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => env('ADMIN_DIR'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'settings'], function () {

            Route::get('/', [
                'as' => 'settings.options',
                'uses' => 'SettingController@getOptions',
            ]);

            Route::post('/edit', [
                'as' => 'settings.edit',
                'uses' => 'SettingController@postEdit',
            ]);

        });
    });
    
});