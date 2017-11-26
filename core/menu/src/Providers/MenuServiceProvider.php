<?php

namespace Botble\Menu\Providers;

use Botble\Base\Supports\Helper;
use Botble\Menu\Facades\MenuFacade;
use Botble\Menu\Models\MenuContent;
use Botble\Menu\Models\MenuNode;
use Botble\Menu\Repositories\Caches\MenuContentCacheDecorator;
use Botble\Menu\Repositories\Caches\MenuNodeCacheDecorator;
use Botble\Menu\Repositories\Eloquent\MenuContentRepository;
use Botble\Menu\Repositories\Eloquent\MenuNodeRepository;
use Botble\Menu\Repositories\Interfaces\MenuContentInterface;
use Botble\Menu\Repositories\Interfaces\MenuInterface;
use Botble\Menu\Models\Menu as MenuModel;
use Botble\Menu\Repositories\Caches\MenuCacheDecorator;
use Botble\Menu\Repositories\Eloquent\MenuRepository;
use Botble\Support\Services\Cache\Cache;
use Botble\Menu\Repositories\Interfaces\MenuNodeInterface;
use Illuminate\Foundation\AliasLoader;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * Register the service provider.
     *
     * @return void
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(MenuInterface::class, function () {
                return new MenuCacheDecorator(new MenuRepository(new MenuModel()), new Cache($this->app['cache'], MenuRepository::class));
            });

            $this->app->singleton(MenuContentInterface::class, function () {
                return new MenuContentCacheDecorator(new MenuContentRepository(new MenuContent()), new Cache($this->app['cache'], MenuContentRepository::class));
            });

            $this->app->singleton(MenuNodeInterface::class, function () {
                return new MenuNodeCacheDecorator(new MenuNodeRepository(new MenuNode()), new Cache($this->app['cache'], MenuNodeRepository::class));
            });
        } else {
            $this->app->singleton(MenuInterface::class, function () {
                return new MenuRepository(new MenuModel());
            });

            $this->app->singleton(MenuContentInterface::class, function () {
                return new MenuContentRepository(new MenuContent());
            });

            $this->app->singleton(MenuNodeInterface::class, function () {
                return new MenuNodeRepository(new MenuNode());
            });
        }

        AliasLoader::getInstance()->alias('Menu', MenuFacade::class);

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/menu.php', 'menu');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'menu');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'menu');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/menu')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/menu')], 'lang');
            $this->publishes([__DIR__ . '/../../config/menu.php' => config_path('menu.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core'),], 'assets');
        }
    }
}
