<?php

Route::group(['namespace' => 'Botble\Dashboard\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {

        Route::get('/', [
            'as' => 'dashboard.index',
            'uses' => 'DashboardController@getDashboard',
        ]);

        Route::group(['prefix' => 'widgets', 'permission' => false], function () {

            Route::post('/edit', [
                'as' => 'dashboard.edit_widget_settings',
                'uses' => 'DashboardController@postEditWidgetSettings',
            ]);

            Route::get('/hide', [
                'as' => 'dashboard.hide_widget',
                'uses' => 'DashboardController@getHideWidget',
            ]);

            Route::post('/hides', [
                'as' => 'dashboard.hide_widgets',
                'uses' => 'DashboardController@postHideWidgets',
            ]);

            Route::post('/order', [
                'as' => 'dashboard.update_widget_order',
                'uses' => 'DashboardController@postUpdateWidgetOrder',
            ]);

            Route::post('/setting-item', [
                'as' => 'dashboard.edit_widget_setting_item',
                'uses' => 'DashboardController@postEditWidgetSettingItem',
            ]);

        });

    });
    
});