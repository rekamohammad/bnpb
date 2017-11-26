<?php

Route::group(['namespace' => 'Botble\AuditLog\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'audit-log', 'permission' => false], function () {

            Route::get('/widgets/activities', [
                'as' => 'audit-log.widget.activities',
                'uses' => 'AuditLogController@getWidgetActivities'
            ]);

        });
    });

});