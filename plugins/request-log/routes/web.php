<?php

Route::group(['namespace' => 'Botble\RequestLog\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'request-log', 'permission' => false], function () {

            Route::get('/widgets/request-errors', [
                'as' => 'request-log.widget.request-errors',
                'uses' => 'RequestLogController@getWidgetRequestErrors'
            ]);

        });
    });

});