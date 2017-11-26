<?php

Route::group(['namespace' => 'Botble\Menu\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'menus'], function () {

            Route::get('/', [
                'as' => 'menus.list',
                'uses' => 'MenuController@getList',
            ]);

            Route::get('/show/{id}', [
                'as' => 'menus.show',
                'uses' => 'MenuController@getShow',
            ]);

            Route::get('/create', [
                'as' => 'menus.create',
                'uses' => 'MenuController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'menus.create',
                'uses' => 'MenuController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'menus.edit',
                'uses' => 'MenuController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'menus.edit',
                'uses' => 'MenuController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'menus.delete',
                'uses' => 'MenuController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'menus.delete.many',
                'uses' => 'MenuController@postDeleteMany',
                'permission' => 'menus.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'menus.change.status',
                'uses' => 'MenuController@postChangeStatus',
                'permission' => 'menus.edit',
            ]);
        });

    });
    
});