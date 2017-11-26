<?php

namespace Botble\Page\Providers;

use Botble\Base\Events\SessionStarted;
use Botble\Base\Supports\Helper;
use Botble\Page\Models\Page;
use Botble\Page\Repositories\Caches\PageCacheDecorator;
use Botble\Page\Repositories\Eloquent\PageRepository;
use Botble\Page\Repositories\Interfaces\PageInterface;
use Botble\Support\Services\Cache\Cache;
use Event;
use Illuminate\Support\ServiceProvider;

/**
 * Class PageServiceProvider
 * @package Botble\Page
 * @author Sang Nguyen
 * @since 02/07/2016 09:50 AM
 */
class PageServiceProvider extends ServiceProvider
{
    /**
     * @var \Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * @author Sang Nguyen
     */
    public function register()
    {
        if (setting('enable_cache', false)) {
            $this->app->singleton(PageInterface::class, function () {
                return new PageCacheDecorator(new PageRepository(new Page()), new Cache($this->app['cache'], PageRepository::class));
            });
        } else {
            $this->app->singleton(PageInterface::class, function () {
                return new PageRepository(new Page());
            });
        }

        Helper::autoload(__DIR__ . '/../../helpers');
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/page.php', 'pages');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'pages');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'pages');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/pages')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/pages')], 'lang');
            $this->publishes([__DIR__ . '/../../config/page.php' => config_path('page.php')], 'config');
        }

        $this->app->register(HookServiceProvider::class);

        Event::listen(SessionStarted::class, function () {
            dashboard_menu()->registerItem([
                'id' => 'cms-core-page',
                'priority' => 2,
                'parent_id' => null,
                'name' => trans('pages::pages.menu_name'),
                'icon' => 'fa fa-book',
                'url' => route('pages.list'),
                'permissions' => ['pages.list'],
            ]);
        });
    }
}
