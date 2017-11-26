<?php

Route::group(['namespace' => 'Botble\ACL\Http\Controllers', 'middleware' => 'web'], function () {

    Route::group(['prefix' => config('cms.admin_dir')], function () {

        Route::group(['middleware' => 'guest'], function () {

            Route::get('/login', [
                'as' => 'access.login',
                'uses' => 'AuthController@getLogin',
            ]);

            Route::post('/login', [
                'as' => 'access.login',
                'uses' => 'AuthController@postLogin',
            ]);

            Route::get('/forgot-password', [
                'as' => 'access.forgot-password',
                'uses' => 'AuthController@getForgotPassword',
            ]);

            Route::post('/forgot-password', [
                'as' => 'access.forgot-password',
                'uses' => 'AuthController@postForgotPassword',
            ]);

            Route::get('/reset-password', [
                'as' => 'access.reset-password',
                'uses' => 'AuthController@getResetPassword',
            ]);

            Route::post('/reset-password', [
                'as' => 'access.reset-password',
                'uses' => 'AuthController@postResetPassword',
            ]);

            Route::get('/invite/accept/{token}', [
                'as' => 'invite.accept',
                'uses' => 'AuthController@getAcceptInvite',
            ]);

            Route::post('/invite/accept', [
                'as' => 'invite.post.accept',
                'uses' => 'AuthController@postAcceptInvite',
            ]);

        });

        Route::group(['middleware' => 'auth'], function () {

            Route::get('/logout', [
                'as' => 'access.logout',
                'uses' => 'AuthController@getLogout',
                'permission' => false,
            ]);

        });
    });

    Route::get('auth/{provider}', ['as' => 'auth.social', 'uses' => 'AuthController@redirectToProvider']);
    Route::get('auth/callback/{provider}', ['as' => 'auth.social.callback', 'uses' => 'AuthController@handleProviderCallback']);

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {

        Route::group(['prefix' => 'system/users'], function () {

            Route::get('/', [
                'as' => 'users.list',
                'uses' => 'UserController@getList',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'users.delete',
                'uses' => 'UserController@getDelete',
            ]);

            Route::get('/create', [
                'as' => 'users.create',
                'uses' => 'UserController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'users.create',
                'uses' => 'UserController@postCreate',
            ]);

            Route::post('/delete-many', [
                'as' => 'users.delete.many',
                'uses' => 'UserController@postDeleteMany',
                'permission' => 'users.delete',
            ]);

            Route::post('/change-status', [
                'as' => 'users.change.status',
                'uses' => 'UserController@postChangeStatus',
                'permission' => 'users.edit',
            ]);

            Route::post('/update-profile/{id}', [
                'as' => 'users.update-profile',
                'uses' => 'UserController@postUpdateProfile',
                'permission' => false,
                'middleware' => 'preventDemo',
            ]);

            Route::post('/modify-profile-image', [
                'as' => 'users.profile.image',
                'uses' => 'UserController@postModifyProfileImage',
                'permission' => false,
            ]);

            Route::post('/change-password/{id}', [
                'as' => 'users.change-password',
                'uses' => 'UserController@postChangePassword',
                'permission' => false,
                'middleware' => 'preventDemo',
            ]);

            Route::get('/profile/{id}', [
                'as' => 'user.profile.view',
                'uses' => 'UserController@getUserProfile',
                'permission' => false,
            ]);

            Route::post('/invite', [
                'as' => 'invite.user',
                'uses' => 'UserController@postInviteUser',
                'permission' => 'users.create',
            ]);

        });

        Route::group(['prefix' => 'system/super-users', 'permission' => 'superuser'], function () {

            Route::get('/', [
                'as' => 'users-supers.list',
                'uses' => 'SuperUserController@getList',
            ]);

            Route::get('delete/{id}', [
                'as' => 'users-supers.delete',
                'uses' => 'SuperUserController@getDelete',
                'middleware' => 'preventDemo',
            ]);

            Route::post('delete-many', [
                'as' => 'users-supers.delete.many',
                'uses' => 'SuperUserController@postDeleteMany',
                'middleware' => 'preventDemo',
            ]);

            Route::post('create', [
                'as' => 'users-supers.create',
                'uses' => 'SuperUserController@postCreate',
            ]);

        });

    });

    Route::group(['prefix' => config('cms.admin_dir'), 'middleware' => 'auth'], function () {
        Route::group(['prefix' => 'system/features', 'permission' => 'superuser'], function () {

            Route::get('/', [
                'as' => 'system.feature.list',
                'uses' => 'FeatureController@getList',
            ]);

            Route::get('/edit', [
                'as' => 'system.feature.edit',
                'uses' => 'FeatureController@getEdit',
            ]);

            Route::post('/edit', [
                'as' => 'system.feature.edit',
                'uses' => 'FeatureController@postEdit',
            ]);
        });

        Route::group(['prefix' => 'system/roles'], function () {

            Route::get('/', [
                'as' => 'roles.list',
                'uses' => 'RoleController@getList',
            ]);

            Route::get('/delete/{id}', [
                'as' => 'roles.delete',
                'uses' => 'RoleController@getDelete',
            ]);

            Route::post('/delete/many', [
                'as' => 'roles.delete.many',
                'uses' => 'RoleController@postDeleteMany',
                'permission' => 'roles.delete',
            ]);

            Route::get('/edit/{id}', [
                'as' => 'roles.edit',
                'uses' => 'RoleController@getEdit',
            ]);

            Route::post('/edit/{id}', [
                'as' => 'roles.edit',
                'uses' => 'RoleController@postEdit',
            ]);

            Route::get('/create', [
                'as' => 'roles.create',
                'uses' => 'RoleController@getCreate',
            ]);

            Route::post('/create', [
                'as' => 'roles.create',
                'uses' => 'RoleController@postCreate',
            ]);

            Route::get('/duplicate/{id}', [
                'as' => 'roles.duplicate',
                'uses' => 'RoleController@getDuplicate',
                'permission' => 'roles.create',
            ]);

            Route::get('/json', [
                'as' => 'roles.list.json',
                'uses' => 'RoleController@getJson',
                'permission' => 'roles.list',
            ]);

            Route::post('/assign', [
                'as' => 'roles.assign',
                'uses' => 'RoleController@postAssignMember',
                'permission' => 'roles.edit',
            ]);

        });
    });

    Route::get('/admin-language/{alias}', [
        'as' => 'admin.language',
        'uses' => 'UserController@getLanguage'
    ]);

    Route::get('/admin-theme/{theme}', [
        'as' => 'admin.theme',
        'uses' => 'UserController@getTheme'
    ]);
    
});

Route::group(apply_filters(BASE_FILTER_GROUP_PUBLIC_ROUTE, []), function () {
    Route::group(['namespace' => 'Botble\ACL\Http\Controllers', 'middleware' => ['web', 'guest']], function () {
        Route::get('/login', [
            'as' => 'public.access.login',
            'uses' => 'PublicController@getLogin',
        ]);

        Route::post('/login', [
            'as' => 'public.access.login',
            'uses' => 'PublicController@postLogin',
        ]);

        Route::get('/forgot-password', [
            'as' => 'public.access.forgot-password',
            'uses' => 'PublicController@getForgotPassword',
        ]);

        Route::post('/forgot-password', [
            'as' => 'public.access.forgot-password',
            'uses' => 'PublicController@postForgotPassword',
        ]);

        Route::get('/reset-password', [
            'as' => 'public.access.reset-password',
            'uses' => 'PublicController@getResetPassword',
        ]);

        Route::post('/reset-password', [
            'as' => 'public.access.reset-password',
            'uses' => 'PublicController@postResetPassword',
        ]);

        Route::get('/register', [
            'as' => 'public.access.register',
            'uses' => 'PublicController@getRegister',
        ]);

        Route::post('/register', [
            'as' => 'public.access.register',
            'uses' => 'PublicController@postRegister',
        ]);

        Route::get('/activation/{code}/{username}', [
            'as' => 'public.access.activation',
            'uses' => 'PublicController@getActivation',
        ]);
    });

    Route::group(['namespace' => 'Botble\ACL\Http\Controllers', 'middleware' => ['web', 'auth'], 'permission' => false], function () {

        Route::get('/account/overview', [
            'as' => 'public.account.overview',
            'uses' => 'MyAccountController@getOverview',
        ]);

        Route::get('/account/edit', [
            'as' => 'public.account.edit',
            'uses' => 'MyAccountController@getEditAccount',
        ]);

        Route::post('/account/edit', [
            'as' => 'public.account.edit',
            'uses' => 'MyAccountController@postEditAccount',
        ]);

        Route::get('/account/change-password', [
            'as' => 'public.account.change-password',
            'uses' => 'MyAccountController@getChangePassword',
        ]);

        Route::post('/account/change-password', [
            'as' => 'public.account.post.change-password',
            'uses' => 'MyAccountController@postChangePassword',
        ]);

        Route::get('/account/change-avatar', [
            'as' => 'public.account.change-avatar',
            'uses' => 'MyAccountController@getChangeProfileImage',
        ]);

        Route::post('/account/change-avatar', [
            'as' => 'public.account.change-avatar',
            'uses' => 'MyAccountController@postChangeProfileImage',
        ]);

        Route::get('/logout', [
            'as' => 'public.access.logout',
            'uses' => 'PublicController@getLogout',
        ]);
    });
});