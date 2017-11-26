<?php

Route::group(['namespace' => 'Botble\LogViewer\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'system/logs', 'permission' => false], function () {

            Route::get('/', [
                'as' => 'log-viewer::dashboard',
                'uses' => 'LogViewerController@index',
            ]);

            Route::get('/list', [
                'as' => 'log-viewer::logs.list',
                'uses' => 'LogViewerController@listLogs',
            ]);

            Route::delete('delete', [
                'as' => 'log-viewer::logs.delete',
                'uses' => 'LogViewerController@delete',
            ]);

            Route::group([
                'prefix' => '{date}',
            ], function () {
                Route::get('/', [
                    'as' => 'log-viewer::logs.show',
                    'uses' => 'LogViewerController@show',
                ]);

                Route::get('download', [
                    'as' => 'log-viewer::logs.download',
                    'uses' => 'LogViewerController@download',
                ]);

                Route::get('{level}', [
                    'as' => 'log-viewer::logs.filter',
                    'uses' => 'LogViewerController@showByLevel',
                ]);
            });

        });
    });
    
});