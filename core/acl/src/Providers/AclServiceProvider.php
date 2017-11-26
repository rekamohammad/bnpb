<?php

namespace Botble\ACL\Providers;

use Botble\ACL\Models\Feature;
use Botble\ACL\Models\Invite;
use Botble\ACL\Models\Role;
use Botble\ACL\Models\RoleFlag;
use Botble\ACL\Models\RoleUser;
use Botble\ACL\Models\User;
use Botble\ACL\Models\PermissionFlag;
use Botble\ACL\Repositories\Caches\FeatureCacheDecorator;
use Botble\ACL\Repositories\Caches\InviteCacheDecorator;
use Botble\ACL\Repositories\Caches\PermissionCacheDecorator;
use Botble\ACL\Repositories\Caches\RoleCacheDecorator;
use Botble\ACL\Repositories\Caches\RoleFlagCacheDecorator;
use Botble\ACL\Repositories\Caches\RoleUserCacheDecorator;
use Botble\ACL\Repositories\Eloquent\FeatureRepository;
use Botble\ACL\Repositories\Eloquent\InviteRepository;
use Botble\ACL\Repositories\Eloquent\PermissionRepository;
use Botble\ACL\Repositories\Eloquent\RoleFlagRepository;
use Botble\ACL\Repositories\Eloquent\RoleRepository;
use Botble\ACL\Repositories\Eloquent\RoleUserRepository;
use Botble\ACL\Repositories\Eloquent\UserRepository;
use Botble\ACL\Repositories\Interfaces\FeatureInterface;
use Botble\ACL\Repositories\Interfaces\InviteInterface;
use Botble\ACL\Repositories\Interfaces\PermissionInterface;
use Botble\ACL\Repositories\Interfaces\RoleFlagInterface;
use Botble\ACL\Repositories\Interfaces\RoleInterface;
use Botble\ACL\Repositories\Interfaces\RoleUserInterface;
use Botble\ACL\Repositories\Interfaces\UserInterface;
use Botble\ACL\Http\Middleware\Authenticate;
use Botble\ACL\Http\Middleware\RedirectIfAuthenticated;
use Botble\Support\Services\Cache\Cache;
use Botble\Base\Supports\Helper;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

/**
 * Class AclServiceProvider
 * @package Botble\ACL
 */
class AclServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    public function register()
    {
        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);

        $this->app->singleton(UserInterface::class, function () {
            return new UserRepository(new User());
        });

        if (setting('enable_cache', false)) {

            $this->app->singleton(PermissionInterface::class, function () {
                return new PermissionCacheDecorator(new PermissionRepository(new PermissionFlag()), new Cache($this->app['cache'], __CLASS__));
            });

            $this->app->singleton(RoleInterface::class, function () {
                return new RoleCacheDecorator(new RoleRepository(new Role()), new Cache($this->app['cache'], __CLASS__));
            });

            $this->app->singleton(FeatureInterface::class, function () {
                return new FeatureCacheDecorator(new FeatureRepository(new Feature()), new Cache($this->app['cache'], __CLASS__));
            });

            $this->app->singleton(RoleUserInterface::class, function () {
                return new RoleUserCacheDecorator(new RoleUserRepository(new RoleUser()), new Cache($this->app['cache'], __CLASS__));
            });

            $this->app->singleton(RoleFlagInterface::class, function () {
                return new RoleFlagCacheDecorator(new RoleFlagRepository(new RoleFlag()), new Cache($this->app['cache'], __CLASS__));
            });

            $this->app->singleton(InviteInterface::class, function () {
                return new InviteCacheDecorator(new InviteRepository(new Invite()), new Cache($this->app['cache'], __CLASS__));
            });
        } else {

            $this->app->singleton(PermissionInterface::class, function () {
                return new PermissionRepository(new PermissionFlag());
            });

            $this->app->singleton(RoleInterface::class, function () {
                return new RoleRepository(new Role());
            });

            $this->app->singleton(RoleUserInterface::class, function () {
                return new RoleUserRepository(new RoleUser());
            });

            $this->app->singleton(RoleFlagInterface::class, function () {
                return new RoleFlagRepository(new RoleFlag());
            });

            $this->app->singleton(FeatureInterface::class, function () {
                return new FeatureRepository(new Feature());
            });

            $this->app->singleton(InviteInterface::class, function () {
                return new InviteRepository(new Invite());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->app->register(EventServiceProvider::class);

        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/acl.php', 'acl');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'acl');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'acl');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/acl')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/acl')], 'lang');
            $this->publishes([__DIR__ . '/../../config/acl.php' => config_path('acl.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core'),], 'assets');
        }

        $this->app->register(HookServiceProvider::class);
    }
}
