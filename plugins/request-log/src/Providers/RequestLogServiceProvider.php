<?php

namespace Botble\RequestLog\Providers;

use Botble\Support\Services\Cache\Cache;
use Botble\RequestLog\Repositories\Caches\RequestLogCacheDecorator;
use Botble\RequestLog\Repositories\Eloquent\RequestLogRepository;
use Botble\RequestLog\Repositories\Interfaces\RequestLogInterface;
use Illuminate\Support\ServiceProvider;
use Botble\RequestLog\Models\RequestLog as RequestLogModel;

/**
 * Class RequestLogServiceProvider
 * @package Botble\RequestLog
 * @author Sang Nguyen
 * @since 02/07/2016 09:50 AM
 */
class RequestLogServiceProvider extends ServiceProvider
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
            $this->app->singleton(RequestLogInterface::class, function () {
                return new RequestLogCacheDecorator(new RequestLogRepository(new RequestLogModel()), new Cache($this->app['cache'], RequestLogRepository::class));
            });
        } else {
            $this->app->singleton(RequestLogInterface::class, function () {
                return new RequestLogRepository(new RequestLogModel());
            });
        }
    }

    /**
     * Boot the service provider.
     * @author Sang Nguyen
     */
    public function boot()
    {
        $this->app->register(EventServiceProvider::class);

        $this->loadRoutesFrom(__DIR__.'/../../routes/web.php');
        $this->mergeConfigFrom(__DIR__ . '/../../config/request-log.php', 'request-logs');
        $this->loadViewsFrom(__DIR__ . '/../../resources/views', 'request-logs');
        $this->loadTranslationsFrom(__DIR__ . '/../../resources/lang', 'request-logs');

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

            $this->publishes([__DIR__ . '/../../resources/lang' => resource_path('lang/vendor/request-log')], 'lang');
            $this->publishes([__DIR__ . '/../../resources/views' => resource_path('views/vendor/request-log')], 'views');
            $this->publishes([__DIR__ . '/../../config/request-log.php' => config_path('request-log.php')], 'config');
        }

        $this->app->register(HookServiceProvider::class);
    }
}
