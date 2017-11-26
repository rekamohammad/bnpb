<?php

namespace Botble\Dashboard\Providers;

use Botble\Support\Services\Cache\Cache;
use Botble\Base\Supports\Helper;
use Botble\Dashboard\Models\DashboardWidget;
use Botble\Dashboard\Models\DashboardWidgetSetting;
use Botble\Dashboard\Repositories\Caches\DashboardWidgetCacheDecorator;
use Botble\Dashboard\Repositories\Caches\DashboardWidgetSettingCacheDecorator;
use Botble\Dashboard\Repositories\Eloquent\DashboardWidgetRepository;
use Botble\Dashboard\Repositories\Eloquent\DashboardWidgetSettingRepository;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetInterface;
use Botble\Dashboard\Repositories\Interfaces\DashboardWidgetSettingInterface;
use Illuminate\Support\ServiceProvider;

/**
 * Class DashboardServiceProvider
 * @package Botble\Dashboard
 * @author Sang Nguyen
 * @since 02/07/2016 09:50 AM
 */
class DashboardServiceProvider extends ServiceProvider
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
            $this->app->singleton(DashboardWidgetInterface::class, function () {
                return new DashboardWidgetCacheDecorator(new DashboardWidgetRepository(new DashboardWidget()), new Cache($this->app['cache'], DashboardWidgetRepository::class));
            });

            $this->app->singleton(DashboardWidgetSettingInterface::class, function () {
                return new DashboardWidgetSettingCacheDecorator(new DashboardWidgetSettingRepository(new DashboardWidgetSetting()), new Cache($this->app['cache'], DashboardWidgetSettingRepository::class));
            });
        } else {
            $this->app->singleton(DashboardWidgetInterface::class, function () {
                return new DashboardWidgetRepository(new DashboardWidget());
            });

            $this->app->singleton(DashboardWidgetSettingInterface::class, function () {
                return new DashboardWidgetSettingRepository(new DashboardWidgetSetting());
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
        $this->mergeConfigFrom(__DIR__ . '/../../config/dashboard.php', 'dashboard');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'dashboard');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'dashboard');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/dashboard')], 'views');
            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/dashboard')], 'lang');
            $this->publishes([__DIR__ . '/../../config/dashboard.php' => config_path('dashboard.php')], 'config');
            $this->publishes([__DIR__ . '/../../resources/assets' => resource_path('assets/core')], 'resources');
            $this->publishes([__DIR__ . '/../../public/assets' => public_path('vendor/core'),], 'assets');
        }
    }
}
