<?php

Route::group(['namespace' => 'Botble\Language\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'languages'], function () {

            Route::get('/', [
                'as' => 'languages.list',
                'uses' => 'LanguageController@getList',
            ]);

            Route::post('/store', [
                'as' => 'languages.store',
                'uses' => 'LanguageController@postStore',
            ]);

            Route::post('/edit', [
                'as' => 'languages.edit',
                'uses' => 'LanguageController@postEdit',
            ]);

            Route::post('/change-item-language', [
                'as' => 'languages.change.item.language',
                'uses' => 'LanguageController@postChangeItemLanguage',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'languages.delete',
                'uses' => 'LanguageController@getDelete',
            ]);

            Route::get('/set-default', [
                'as' => 'languages.set.default',
                'uses' => 'LanguageController@getSetDefault',
            ]);

            Route::get('/get', [
                'as' => 'languages.get',
                'uses' => 'LanguageController@getLanguage',
            ]);

            Route::post('/settings', [
                'as' => 'languages.settings',
                'uses' => 'LanguageController@postEditSettings',
            ]);

            Route::get('/change-data-language/{locale}', [
                'as' => 'languages.change.data.language',
                'uses' => 'LanguageController@getChangeDataLanguage',
            ]);
        });
    });

    Route::get('/language/{code}', [
        'as' => 'languages.change.language',
        'uses' => 'LanguageController@getChangeLanguage',
        'permission' => false
    ]);
    
});