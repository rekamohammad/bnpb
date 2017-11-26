<?php

Route::group(['namespace' => 'Botble\CustomField\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'custom-fields'], function () {

            Route::get('/', [
                'as' => 'custom-fields.list',
                'uses' => 'CustomFieldController@getList',
            ]);

            Route::get('/create', [
                'as' => 'custom-fields.create',
                'uses' => 'CustomFieldController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'custom-fields.create',
                'uses' => 'CustomFieldController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'custom-fields.edit',
                'uses' => 'CustomFieldController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'custom-fields.edit',
                'uses' => 'CustomFieldController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'custom-fields.delete',
                'uses' => 'CustomFieldController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'custom-fields.delete.many',
                'uses' => 'CustomFieldController@postDeleteMany',
                'permission' => 'custom-fields.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'custom-fields.change.status',
                'uses' => 'CustomFieldController@postChangeStatus',
                'permission' => 'custom-fields.delete',
            ]);

        });

    });

});