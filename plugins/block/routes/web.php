<?php

Route::group(['namespace' => 'Botble\Block\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'block'], function () {

            Route::get('/', [
                'as' => 'block.list',
                'uses' => 'BlockController@getList',
            ]);

            Route::get('/create', [
                'as' => 'block.create',
                'uses' => 'BlockController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'block.create',
                'uses' => 'BlockController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'block.edit',
                'uses' => 'BlockController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'block.edit',
                'uses' => 'BlockController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'block.delete',
                'uses' => 'BlockController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'block.delete.many',
                'uses' => 'BlockController@postDeleteMany',
                'permission' => 'block.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'block.change.status',
                'uses' => 'BlockController@postChangeStatus',
                'permission' => 'block.edit',
            ]);
        });
    });
    
});