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

        Route::group(['prefix' => 'album'], function () {

            Route::get('/', [
                'as' => 'album.list',
                'uses' => 'AlbumController@getList',
            ]);

            Route::get('/create', [
                'as' => 'album.create',
                'uses' => 'AlbumController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'album.create',
                'uses' => 'AlbumController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'album.edit',
                'uses' => 'AlbumController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'album.edit',
                'uses' => 'AlbumController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'album.delete',
                'uses' => 'AlbumController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'album.delete.many',
                'uses' => 'AlbumController@postDeleteMany',
                'permission' => 'album.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'album.change.status',
                'uses' => 'AlbumController@postChangeStatus',
                'permission' => 'album.edit',
            ]);
        });

        Route::group(['prefix' => 'diorama'], function () {

            Route::get('/', [
                'as' => 'diorama.list',
                'uses' => 'DioramaController@getList',
            ]);

            Route::get('/create', [
                'as' => 'diorama.create',
                'uses' => 'DioramaController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'diorama.create',
                'uses' => 'DioramaController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'diorama.edit',
                'uses' => 'DioramaController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'diorama.edit',
                'uses' => 'DioramaController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'diorama.delete',
                'uses' => 'DioramaController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'diorama.delete.many',
                'uses' => 'DioramaController@postDeleteMany',
                'permission' => 'diorama.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'diorama.change.status',
                'uses' => 'DioramaController@postChangeStatus',
                'permission' => 'diorama.edit',
            ]);

            Route::post('/create-slug', [
                'as' => 'diorama.create.slug',
                'uses' => 'DioramaController@postCreateSlug',
                'permission' => 'diorama.create',
            ]);

            Route::get('/widgets/recent-posts', [
                'as' => 'diorama.widget.recent-posts',
                'uses' => 'DioramaController@getWidgetRecentPosts',
                'permission' => false,
            ]);
        });

        Route::group(['prefix' => 'publikasi'], function () {

            Route::get('/', [
                'as' => 'publikasi.list',
                'uses' => 'PublikasiController@getList',
            ]);

            Route::get('/create', [
                'as' => 'publikasi.create',
                'uses' => 'PublikasiController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'publikasi.create',
                'uses' => 'PublikasiController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'publikasi.edit',
                'uses' => 'PublikasiController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'publikasi.edit',
                'uses' => 'PublikasiController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'publikasi.delete',
                'uses' => 'PublikasiController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'publikasi.delete.many',
                'uses' => 'PublikasiController@postDeleteMany',
                'permission' => 'publikasi.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'publikasi.change.status',
                'uses' => 'PublikasiController@postChangeStatus',
                'permission' => 'publikasi.edit',
            ]);

            Route::post('/create-slug', [
                'as' => 'publikasi.create.slug',
                'uses' => 'PublikasiController@postCreateSlug',
                'permission' => 'publikasi.create',
            ]);

            Route::get('/widgets/recent-posts', [
                'as' => 'publikasi.widget.recent-posts',
                'uses' => 'PublikasiController@getWidgetRecentPosts',
                'permission' => false,
            ]);
        });

        Route::group(['prefix' => 'infografis'], function () {

            Route::get('/', [
                'as' => 'infografis.list',
                'uses' => 'InfografisController@getList',
            ]);

            Route::get('/create', [
                'as' => 'infografis.create',
                'uses' => 'InfografisController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'infografis.create',
                'uses' => 'InfografisController@postCreate',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'infografis.edit',
                'uses' => 'InfografisController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'infografis.edit',
                'uses' => 'InfografisController@postEdit',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'infografis.delete',
                'uses' => 'InfografisController@getDelete',
            ]);

            Route::post('/delete-many', [
                'as' => 'infografis.delete.many',
                'uses' => 'InfografisController@postDeleteMany',
                'permission' => 'infografis.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'infografis.change.status',
                'uses' => 'InfografisController@postChangeStatus',
                'permission' => 'infografis.edit',
            ]);

            Route::post('/create-slug', [
                'as' => 'infografis.create.slug',
                'uses' => 'InfografisController@postCreateSlug',
                'permission' => 'infografis.create',
            ]);

            Route::get('/widgets/recent-posts', [
                'as' => 'infografis.widget.recent-posts',
                'uses' => 'InfografisController@getWidgetRecentPosts',
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
		
		Route::group(['prefix' => 'nasional'], function () {
			Route::get('/', [
                'as' => 'nasional.list',
                'uses' => 'NasionalController@getList',
			]);
			Route::get('/create', [
                'as' => 'nasional.create',
                'uses' => 'NasionalController@getCreate',
            ]);	
			Route::post('/create', [
                'as' => 'nasional.create',
                'uses' => 'NasionalController@postCreate',
           	
            ]);
			Route::get('/edit/{id}', [
                'as' => 'nasional.edit',
                'uses' => 'NasionalController@getEdit',
            ]);
			Route::post('/edit/{id}', [
                'as' => 'nasional.edit',
                'uses' => 'NasionalController@postEdit',
            ]);
			Route::get('/delete/{id}', [
                'as' => 'nasional.delete',
                'uses' => 'NasionalController@getDelete',
            ]);
			Route::post('/delete-many', [
                'as' => 'nasional.delete.many',
                'uses' => 'NasionalController@postDeleteMany',
                'permission' => 'nasional.delete',
            ]);
			Route::post('/change-status', [
                'as' => 'nasional.change.status',
                'uses' => 'NasionalController@postChangeStatus',
                'permission' => 'nasional.change',
            ]);
		});
		
		Route::group(['prefix' => 'Internasional'], function () {
			Route::get('/', [
                'as' => 'internasional.list',
                'uses' => 'InternasionalController@getList',
            ]);
			Route::get('/create', [
                'as' => 'internasional.create',
                'uses' => 'InternasionalController@getCreate',
            ]);	
			Route::post('/create', [
                'as' => 'internasional.create',
                'uses' => 'InternasionalController@postCreate',
           	
            ]);
			Route::get('/edit/{id}', [
                'as' => 'internasional.edit',
                'uses' => 'InternasionalController@getEdit',
            ]);
			Route::post('/edit/{id}', [
                'as' => 'internasional.edit',
                'uses' => 'InternasionalController@postEdit',
            ]);
			Route::get('/delete/{id}', [
                'as' => 'internasional.delete',
                'uses' => 'InternasionalController@getDelete',
            ]);
			Route::post('/delete-many', [
                'as' => 'internasional.delete.many',
                'uses' => 'InternasionalController@postDeleteMany',
                'permission' => 'internasional.delete',
            ]);
			Route::post('/change-status', [
                'as' => 'internasional.change.status',
                'uses' => 'InternasionalController@postChangeStatus',
                'permission' => 'internasional.change',
            ]);
		});
		
		Route::group(['prefix' => 'provinsi'], function () {
			Route::get('/', [
                'as' => 'provinsi.list',
                'uses' => 'ProvinsiController@getList',
            ]);
			Route::get('/create', [
                'as' => 'provinsi.create',
                'uses' => 'ProvinsiController@getCreate',
            ]);	
			Route::post('/create', [
                'as' => 'provinsi.create',
                'uses' => 'ProvinsiController@postCreate',
           	
            ]);
			Route::get('/edit/{id}', [
                'as' => 'provinsi.edit',
                'uses' => 'ProvinsiController@getEdit',
            ]);
			Route::post('/edit/{id}', [
                'as' => 'provinsi.edit',
                'uses' => 'ProvinsiController@postEdit',
            ]);
			Route::get('/delete/{id}', [
                'as' => 'provinsi.delete',
                'uses' => 'ProvinsiController@getDelete',
            ]);
			Route::post('/delete-many', [
                'as' => 'provinsi.delete.many',
                'uses' => 'ProvinsiController@postDeleteMany',
                'permission' => 'provinsi.delete',
            ]);
			Route::post('/change-status', [
                'as' => 'provinsi.change.status',
                'uses' => 'ProvinsiController@postChangeStatus',
                'permission' => 'provinsi.change',
            ]);
		});
		
		Route::group(['prefix' => 'kabupaten'], function () {
			Route::get('/', [
                'as' => 'kabupaten.list',
                'uses' => 'KabupatenController@getList',
            ]);
			Route::get('/create', [
                'as' => 'kabupaten.create',
                'uses' => 'KabupatenController@getCreate',
            ]);	
			Route::post('/create', [
                'as' => 'kabupaten.create',
                'uses' => 'KabupatenController@postCreate',
           	
            ]);
			Route::get('/edit/{id}', [
                'as' => 'kabupaten.edit',
                'uses' => 'KabupatenController@getEdit',
            ]);
			Route::post('/edit/{id}', [
                'as' => 'kabupaten.edit',
                'uses' => 'KabupatenController@postEdit',
            ]);
			Route::get('/delete/{id}', [
                'as' => 'kabupaten.delete',
                'uses' => 'KabupatenController@getDelete',
            ]);
			Route::post('/delete-many', [
                'as' => 'kabupaten.delete.many',
                'uses' => 'KabupatenController@postDeleteMany',
                'permission' => 'kabupaten.delete',
            ]);
			Route::post('/change-status', [
                'as' => 'kabupaten.change.status',
                'uses' => 'KabupatenController@postChangeStatus',
                'permission' => 'kabupaten.change',
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