<?php

Route::group(['namespace' => 'Botble\Blog\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'news'], function () {

            Route::get('/', [
                'as' => 'news.list',
                'uses' => 'NewsController@getList',
            ]);

            Route::get('/create', [
                'as' => 'news.create',
                'uses' => 'NewsController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'news.create',
                'uses' => 'NewsController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'news.edit',
                'uses' => 'NewsController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'news.edit',
                'uses' => 'NewsController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'news.delete',
                'uses' => 'NewsController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'news.delete.many',
                'uses' => 'NewsController@postDeleteMany',
                'permission' => 'news.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'news.change.status',
                'uses' => 'NewsController@postChangeStatus',
                'permission' => 'news.edit',
            ]);

            Route::post('/create-slug', [
                'as' => 'news.create.slug',
                'uses' => 'NewsController@postCreateSlug',
                'permission' => 'news.create',
            ]);

            Route::get('/widgets/recent-posts', [
                'as' => 'news.widget.recent-posts',
                'uses' => 'NewsController@getWidgetRecentPosts',
                'permission' => false,
            ]);
        });

        Route::group(['prefix' => 'posts'], function () {

            Route::get('/', [
                'as' => 'posts.list',
                'uses' => 'PostController@getList',
            ]);

            Route::get('/create', [
                'as' => 'posts.create',
                'uses' => 'PostController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'posts.create',
                'uses' => 'PostController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'posts.edit',
                'uses' => 'PostController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'posts.edit',
                'uses' => 'PostController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'posts.delete',
                'uses' => 'PostController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'posts.delete.many',
                'uses' => 'PostController@postDeleteMany',
                'permission' => 'posts.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'posts.change.status',
                'uses' => 'PostController@postChangeStatus',
                'permission' => 'posts.edit',
            ]);

            Route::post('/create-slug', [
                'as' => 'posts.create.slug',
                'uses' => 'PostController@postCreateSlug',
                'permission' => 'posts.create',
            ]);

            Route::get('/widgets/recent-posts', [
                'as' => 'posts.widget.recent-posts',
                'uses' => 'PostController@getWidgetRecentPosts',
                'permission' => false,
            ]);
        });

        Route::group(['prefix' => 'categories'], function () {

            Route::get('/', [
                'as' => 'categories.list',
                'uses' => 'CategoryController@getList',
            ]);

            Route::get('/create', [
                'as' => 'categories.create',
                'uses' => 'CategoryController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'categories.create',
                'uses' => 'CategoryController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'categories.edit',
                'uses' => 'CategoryController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'categories.edit',
                'uses' => 'CategoryController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'categories.delete',
                'uses' => 'CategoryController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'categories.delete.many',
                'uses' => 'CategoryController@postDeleteMany',
                'permission' => 'categories.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'categories.change.status',
                'uses' => 'CategoryController@postChangeStatus',
                'permission' => 'categories.edit',
            ]);

            Route::post('/create-slug', [
                'as' => 'categories.create.slug',
                'uses' => 'CategoryController@postCreateSlug',
                'permission' => 'categories.create',
            ]);
        });

        Route::group(['prefix' => 'tags'], function () {

            Route::get('/', [
                'as' => 'tags.list',
                'uses' => 'TagController@getList',
            ]);

            Route::get('/create', [
                'as' => 'tags.create',
                'uses' => 'TagController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'tags.create',
                'uses' => 'TagController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'tags.edit',
                'uses' => 'TagController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'tags.edit',
                'uses' => 'TagController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'tags.delete',
                'uses' => 'TagController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'tags.delete.many',
                'uses' => 'TagController@postDeleteMany',
                'permission' => 'tags.delete',
            ]);

            Route::get('/all', [
                'as' => 'tags.all',
                'uses' => 'TagController@getAllTags',
                'permission' => 'tags.list',
            ]);

            Route::post('/create-slug', [
                'as' => 'tags.create.slug',
                'uses' => 'TagController@postCreateSlug',
                'permission' => 'tags.create',
            ]);
        });

        
    });

    Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {

        Route::get('/{slug}.html', [
            'as' => 'public.single.detail',
            'uses' => 'PublicController@getView',
        ]);

        Route::get('/tag/{slug}.html', [
            'as' => 'public.tag',
            'uses' => 'PublicController@getByTag',
        ]);

        Route::get('/author/{slug}', [
            'as' => 'public.author',
            'uses' => 'PublicController@getAuthor',
        ]);

        Route::get('/api/search', [
            'as' => 'public.api.search',
            'uses' => 'PublicController@getApiSearch',
        ]);

        Route::get('/search', [
            'as' => 'public.search',
            'uses' => 'PublicController@getSearch',
        ]);

        Route::get('/feed/blog/json', [
            'as' => 'public.blog.feed.json',
            'uses' => 'PublicController@getJsonFeed',
        ]);

        Route::get('/{slug?}/{p1?}/{p2?}/{p3?}', [
            'as' => 'public.single.detail',
            'uses' => 'PublicController@getView',
        ]);

    });
    
});