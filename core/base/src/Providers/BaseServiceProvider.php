<?php

namespace Botble\Base\Providers;

use Botble\ACL\Providers\AclServiceProvider;
use Botble\Assets\Providers\AssetsServiceProvider;
use Botble\Base\Events\SessionStarted;
use Botble\Base\Exceptions\Handler;
use Botble\Base\Facades\ActionFacade;
use Botble\Base\Facades\AdminBarFacade;
use Botble\Base\Facades\AdminBreadcrumbFacade;
use Botble\Base\Facades\DashboardMenuFacade;
use Botble\Base\Facades\EmailHandlerFacade;
use Botble\Base\Facades\FilterFacade;
use Botble\Base\Facades\MetaBoxFacade;
use Botble\Base\Facades\PageTitleFacade;
use Botble\Base\Http\Middleware\AdminBarMiddleware;
use Botble\Base\Http\Middleware\DisableInDemoMode;
use Botble\Base\Http\Middleware\HttpsProtocol;
use Botble\Base\Http\Middleware\Locale;
use Botble\Base\Http\Middleware\StartSession;
use Botble\Base\Models\MetaBox as MetaBoxModel;
use Botble\Base\Models\Plugin;
use Botble\Base\Repositories\Caches\MetaBoxCacheDecorator;
use Botble\Base\Repositories\Caches\PluginCacheDecorator;
use Botble\Base\Repositories\Eloquent\MetaBoxRepository;
use Botble\Base\Repositories\Eloquent\PluginRepository;
use Botble\Base\Repositories\Interfaces\MetaBoxInterface;
use Botble\Base\Repositories\Interfaces\PluginInterface;
use Botble\Support\Providers\SupportServiceProvider;
use Botble\Support\Services\Cache\Cache;
use Botble\Base\Supports\Helper;
use Botble\Dashboard\Providers\DashboardServiceProvider;
use Botble\Media\Providers\MediaServiceProvider;
use Botble\Menu\Providers\MenuServiceProvider;
use Botble\Page\Providers\PageServiceProvider;
use Botble\SeoHelper\Providers\SeoHelperServiceProvider;
use Botble\Setting\Providers\SettingServiceProvider;
use Botble\Shortcode\Providers\ShortcodeServiceProvider;
use Botble\Theme\Providers\ThemeServiceProvider;
use Botble\Widget\Providers\WidgetServiceProvider;
use Event;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use MetaBox;
use Schema;

class BaseServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register any application services.
     *
     * @return void
     * @author Sang Nguyen
     */
    public function register()
    {

        Helper::autoload(__DIR__ . '/../../helpers');

        $this->app->register(SupportServiceProvider::class);
        $this->app->register(AssetsServiceProvider::class);
        $this->app->register(SettingServiceProvider::class);
        $this->app->register(ShortcodeServiceProvider::class);

        $this->app->singleton(ExceptionHandler::class, Handler::class);

        /**
         * @var Router $router
         */
        $router = $this->app['router'];

        $router->pushMiddlewareToGroup('web', Locale::class);
        $router->pushMiddlewareToGroup('web', HttpsProtocol::class);
        $router->pushMiddlewareToGroup('web', AdminBarMiddleware::class);
        $router->pushMiddlewareToGroup('web', StartSession::class);
        $router->aliasMiddleware('preventDemo', DisableInDemoMode::class);

        $loader = AliasLoader::getInstance();
        $loader->alias('MetaBox', MetaBoxFacade::class);
        $loader->alias('Action', ActionFacade::class);
        $loader->alias('Filter', FilterFacade::class);
        $loader->alias('EmailHandler', EmailHandlerFacade::class);
        $loader->alias('AdminBar', AdminBarFacade::class);
        $loader->alias('PageTitle', PageTitleFacade::class);
        $loader->alias('AdminBreadcrumb', AdminBreadcrumbFacade::class);
        $loader->alias('DashboardMenu', DashboardMenuFacade::class);

        if (setting('enable_cache', false)) {
            $this->app->singleton(MetaBoxInterface::class, function () {
                return new MetaBoxCacheDecorator(new MetaBoxRepository(new MetaBoxModel()), new Cache($this->app['cache'], MetaBoxRepository::class));
            });

            $this->app->singleton(PluginInterface::class, function () {
                return new PluginCacheDecorator(new PluginRepository(new Plugin()), new Cache($this->app['cache'], PluginRepository::class));
            });

        } else {
            $this->app->singleton(MetaBoxInterface::class, function () {
                return new MetaBoxRepository(new MetaBoxModel());
            });

            $this->app->singleton(PluginInterface::class, function () {
                return new PluginRepository(new Plugin());
            });
        }

        $this->app->register(PluginServiceProvider::class);
    }

    /**
     * Boot the service provider.
     * @return void
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->app->register(AclServiceProvider::class);
        $this->app->register(DashboardServiceProvider::class);
        $this->app->register(MediaServiceProvider::class);
        $this->app->register(MenuServiceProvider::class);
        $this->app->register(PageServiceProvider::class);
        $this->app->register(SeoHelperServiceProvider::class);
        $this->app->register(ThemeServiceProvider::class);
        $this->app->register(WidgetServiceProvider::class);
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
        $this->app->register(CommandServiceProvider::class);
        $this->app->register(ThemeManagementServiceProvider::class);
        $this->app->register(BreadcrumbsServiceProvider::class);
        $this->app->register(ComposerServiceProvider::class);

        if ($this->app->environment() == 'local') {
            if (env('ENABLE_DEBUG_BAR', false) == true) {
                $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
            }
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }

        $this->mergeConfigFrom(__DIR__ . '/../../config/cms.php', 'cms');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'bases');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'bases');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/bases')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/bases')], 'lang');
            $this->publishes([__DIR__ . '/../../config/cms.php' => config_path('cms.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core'),], 'assets');
        }

        Schema::defaultStringLength(191);

        $this->app->register(FormServiceProvider::class);

        app()->booted(function () {
            do_action('init');

            add_action(BASE_ACTION_META_BOXES, [MetaBox::class, 'doMetaBoxes'], 8, 3);
        });

        Event::listen(SessionStarted::class, function () {
            $this->registerDefaultMenus();
        });
    }

    /**
     * Add default dashboard menu for core
     * @author Sang Nguyen
     */
    public function registerDefaultMenus()
    {
        dashboard_menu()->registerItem([
                'id' => 'cms-core-dashboard',
                'priority' => 0,
                'parent_id' => null,
                'name' => trans('bases::layouts.dashboard'),
                'icon' => 'fa fa-home',
                'url' => route('dashboard.index'),
                'permissions' => ['dashboard.index'],
            ])
            ->registerItem([
                'id' => 'cms-core-appearance',
                'priority' => 996,
                'parent_id' => null,
                'name' => trans('bases::layouts.appearance'),
                'icon' => 'fa fa-paint-brush',
                'url' => route('theme.list'),
                'permissions' => ['theme.list'],
            ])
            // ->registerItem([
            //     'id' => 'cms-core-theme',
            //     'priority' => 1,
            //     'parent_id' => 'cms-core-appearance',
            //     'name' => trans('bases::layouts.theme'),
            //     'icon' => null,
            //     'url' => route('theme.list'),
            //     'permissions' => ['theme.list'],
            // ])
            ->registerItem([
                'id' => 'cms-core-menu',
                'priority' => 2,
                'parent_id' => 'cms-core-appearance',
                'name' => trans('bases::layouts.menu'),
                'icon' => null,
                'url' => route('menus.list'),
                'permissions' => ['menus.list'],
            ])
            ->registerItem([
                'id' => 'cms-core-widget',
                'priority' => 3,
                'parent_id' => 'cms-core-appearance',
                'name' => trans('bases::layouts.widgets'),
                'icon' => null,
                'url' => route('widgets.list'),
                'permissions' => ['widgets.list'],
            ])
            ->registerItem([
                'id' => 'cms-core-theme-option',
                'priority' => 4,
                'parent_id' => 'cms-core-appearance',
                'name' => trans('bases::layouts.theme_options'),
                'icon' => null,
                'url' => route('theme.options'),
                'permissions' => ['theme.options'],
            ])
            // ->registerItem([
            //     'id' => 'cms-core-plugins',
            //     'priority' => 997,
            //     'parent_id' => null,
            //     'name' => trans('bases::layouts.plugins'),
            //     'icon' => 'fa fa-plug',
            //     'url' => route('plugins.list'),
            //     'permissions' => ['plugins.list'],
            // ])
            ->registerItem([
                'id' => 'cms-core-settings',
                'priority' => 998,
                'parent_id' => null,
                'name' => trans('bases::layouts.settings'),
                'icon' => 'fa fa-cogs',
                'url' => route('settings.options'),
                'permissions' => ['settings.options'],
            ])
            ->registerItem([
                'id' => 'cms-core-settings-general',
                'priority' => 1,
                'parent_id' => 'cms-core-settings',
                'name' => trans('bases::layouts.setting_general'),
                'icon' => null,
                'url' => route('settings.options'),
                'permissions' => ['settings.options'],
            ])
            ->registerItem([
                'id' => 'cms-core-platform-administration',
                'priority' => 999,
                'parent_id' => null,
                'name' => trans('bases::layouts.platform_admin'),
                'icon' => 'fa fa-shield',
                'url' => null,
                'permissions' => ['users.list'],
            ])
            ->registerItem([
                'id' => 'cms-core-feature-access-control',
                'priority' => 1,
                'parent_id' => 'cms-core-platform-administration',
                'name' => trans('bases::layouts.feature_access_control'),
                'icon' => null,
                'url' => route('system.feature.list'),
                'permissions' => ['system.feature.list'],
            ])
            ->registerItem([
                'id' => 'cms-core-role-permission',
                'priority' => 2,
                'parent_id' => 'cms-core-platform-administration',
                'name' => trans('bases::layouts.role_permission'),
                'icon' => null,
                'url' => route('roles.list'),
                'permissions' => ['roles.list'],
            ])
            ->registerItem([
                'id' => 'cms-core-user',
                'priority' => 3,
                'parent_id' => 'cms-core-platform-administration',
                'name' => trans('bases::layouts.user_management'),
                'icon' => null,
                'url' => route('users.list'),
                'permissions' => ['users.list'],
            ])
            ->registerItem([
                'id' => 'cms-core-user-super',
                'priority' => 4,
                'parent_id' => 'cms-core-platform-administration',
                'name' => trans('bases::layouts.super_user_management'),
                'icon' => null,
                'url' => route('users-supers.list'),
                'permissions' => ['users-supers.list'],
            ])
            // ->registerItem([
            //     'id' => 'cms-core-system-information',
            //     'priority' => 5,
            //     'parent_id' => 'cms-core-platform-administration',
            //     'name' => trans('bases::layouts.system_information'),
            //     'icon' => null,
            //     'url' => route('system.info'),
            //     'permissions' => ['system.info'],
            // ])
            ;
    }
}
