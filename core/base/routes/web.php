<?php

Route::group(['namespace' => 'Botble\Base\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth', 'permission' => 'superuser'], function () {

        Route::group(['prefix' => 'system'], function () {

            Route::get('/info', [
                'as' => 'system.info',
                'uses' => 'SystemController@getInfo',
            ]);

        });

        Route::group(['prefix' => 'plugins'], function () {

            Route::get('/', [
                'as' => 'plugins.list',
                'uses' => 'SystemController@getListPlugins',
            ]);

            Route::get('/change}', [
                'as' => 'plugins.change.status',
                'uses' => 'SystemController@getChangePluginStatus',
                'middleware' => 'preventDemo',
            ]);

        });

    });

    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::get('/', [
            'as' => 'public.index',
            'uses' => 'PublicController@getIndex',
        ]);

        Route::get('/{slug}.html', [
            'as' => 'public.single.detail',
            'uses' => 'PublicController@getView',
        ]);

        Route::get('/sitemap.xml', [
            'as' => 'public.sitemap',
            'uses' => 'PublicController@getSiteMap',
        ]);

        Route::get('/feed/blog/json', [
            'as' => 'public.feed.json',
            'uses' => 'PublicController@getJsonFeed',
        ]);

    });
});